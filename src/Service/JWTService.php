<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Exception\JWTException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

class JWTService
{
    private string $secretKey;
    private string $algorithm;

    public function __construct(string $secretKey, string $algorithm = 'HS256')
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
    }

    /**
     * Genera un token JWT.
     *
     * @param array $payload Datos a incluir en el token.
     * @param int $expiration Tiempo de expiración en segundos desde el momento actual.
     * @return string Token JWT generado.
     */
    public function generateToken(array $payload, int $expiration = 43200): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiration;

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Decodifica un token JWT.
     *
     * @param string $token Token JWT a decodificar.
     * @return object Datos decodificados del token.
     * @throws  JWTException el token es inválido o ha expirado.
     */
    public function decodeToken(string $token): object
    {
        return JWT::decode($token, new Key($this->secretKey, $this->algorithm));
    }

    public function isTokenValid(string $token): bool
    {

			try {
				$this->decodeToken($token);
				return true;
			} catch (\Throwable $th) {
				$code = 401;
				if($th instanceof BeforeValidException || $th instanceof SignatureInvalidException) {
					$code = 400;
				}
				throw new JWTException($th->getMessage(), $code);
			}
    }
}