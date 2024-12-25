<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\FormException;
use App\Exception\UnauthorizedException;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationService extends AbstractService
{
	const IS_AUTHENTICATED_FULLY = "IS_AUTHENTICATED_FULLY";
	const IS_AUTHENTICATED_ANONYMOUSLY = "IS_AUTHENTICATED_ANONYMOUSLY";
	const ROLE_ADMIN = "ROLE_ADMIN";
	const ROLE_USER = "ROLE_USER";
	const AUTHORIZATION_TYPE="Bearer";

	/**
	 * @throws BadRequestException Token incorrect or undefined.
	 * @throws FormException Error formdata (body|entity).
	 */
	public function signIn(UserRepository $userRepository): User
	{
		$body = $this->getBody();
		if(!isset($body->token) || !$this->checkToken($body->token)){
			throw new BadRequestException("The token is incorrectly formatted or undefined", Response::HTTP_BAD_REQUEST);
		}
		$credentials = $this->getCredentials($body->token);
		$criteria = ['username' => $credentials->username];
		$user = $userRepository->findOneBy($criteria);
		if(!$user){
			throw new FormException(
				'Username is incorrect.',
				['username' => 'The given user does not exist'],
				Response::HTTP_NOT_FOUND
			);
		}
		if($user->getPassword() != $credentials->password){
			throw new FormException(
				'Password is incorrect.',
				['password' => 'Password is incorrect'],
				Response::HTTP_NOT_FOUND
			);
		}
		$token = new UsernamePasswordToken($user, 'main', $user->getRoles());
		$this->container->get('security.token_storage')->setToken($token);
		$this->getSession()->set('_security_main', serialize($token));
		$token = $this->generateTokenBySignOut($user);
		$user->setToken($token);
		return $user;
	}

	/**
	 * @throws BadRequestException Undefined token or token is incorrectly formatted
	 * @throws UnauthorizedException Undefined UserInterface.
	 */
	public function signOut(Security $security): void
	{
		$this->checkTokenSignOut();
		$security->logout(false);
	}

	public function generateTokenBySignOut(User $user)
	{
		$key = base64_encode(json_encode([
			"key" => $user->getId()
		]));
		$jwt = self::getJWT();
		return $jwt->generateToken(compact('key'));
	}

	/**
	 * @throws JWTException token incorrect or expirated.
	 */
	private function decodeTokenBySignOut(string $token): string
	{
		$jwt = self::getJWT();
		$payload = $jwt->decodeToken($token);
		$payload = base64_decode($payload->key);
		$payload = json_decode($payload)->key ?? null;
		if(!$payload) {
			throw new BadRequestException("Undefined key.", 400);
		}
		return $payload;
	}

	static function getJWT()
	{
		return new JWTService($_ENV['JWT_KEY'], $_ENV['JWT_ALGORITHM']);

	}
 
	private function checkTokenSignOut()
	{
		$user = $this->getUser();
		if(!$user){
			throw new UnauthorizedException("Unauthorized: undefined user.");
		}
		$request = $this->getRequest();
		$token = $request->query->get('token');
		if(!$token){
			$token = self::getTokenFromHeaders($this->getRequest());
		}
		$payload = $this->decodeTokenBySignOut($token);
		$method = 'getId';
		if($user->$method() != $payload){
			throw new BadRequestException("The token is incorrectly formatted", Response::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * @throws BadRequestException invalid json.
	 */
	private function checkToken(string $token): bool
	{
		$params = self::decodeToken($token);
		return isset($params->username) && isset($params->password);
	}

	/**
	 * @throws UnauthorizedException unauthorized.
	 */
	static function getTokenFromHeaders(Request $request): string
	{
			$type = self::AUTHORIZATION_TYPE;
			$authorizationHeader = $request->headers->get('Authorization');
			if(!$authorizationHeader || !str_starts_with($authorizationHeader, "$type ")){
				throw new UnauthorizedException("Undefined authorization token or authorization type is incorrect(Expected \"$type\")");
			}
			return substr($authorizationHeader, 7);
	}

	/**
	 * @throws BadRequestException invalid json.
	 */
	static function decodeToken(string $token): object
	{
		$json = json_decode(base64_decode($token));
		if(json_last_error() != JSON_ERROR_NONE){
			throw new BadRequestException("The token is incorrectly formatted (invalid json)", 400);
		}
		return $json;
	}

	/**
	 * @throws BadRequestException invalid json.
	 */
	private function getCredentials(string $token): object
	{
		$params = self::decodeToken($token);
		$params->password = self::encrypPassword($params->password);
		return $params;
	}

	static function encrypPassword(string $password)
	{
		$salt = $_ENV['APP_SALT'];
		$password = "{$salt}{$password}";
		return hash('sha256', $password);
	}
}