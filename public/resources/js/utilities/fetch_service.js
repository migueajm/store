class FetchServiceError extends Error {
  constructor(message, statusCode = null, statusText = '') {
    super(message);
    this.name = 'FetchServiceError';
    this.statusCode = statusCode;
    this.statusText = statusText;
    this.stack = (new Error()).stack;
  }
}

class FetchService {
  constructor(baseURL = '') {
    this.baseURL = baseURL;
    this.defaultHeaders = {
      'Content-Type': 'application/json',
    };
  }

  /**
   * Realiza una solicitud HTTP con reintentos y manejo de tiempo de espera.
   * @param {string} method - Método HTTP (GET, POST, etc.)
   * @param {string} endpoint - URL del endpoint
   * @param {object} [data=null] - Datos a enviar en la solicitud
   * @param {object} [customHeaders={}] - Encabezados personalizados
   * @param {number} [retries=3] - Número de reintentos
   * @param {number} [timeout=5000] - Tiempo máximo de espera en milisegundos
   * @returns {Promise} - Respuesta de la solicitud
   */
  async request(method, endpoint, data = null, customHeaders = {}, retries = 3, timeout = 5000) {
    const url = `${this.baseURL}${endpoint}`;
    const options = {
      method,
      headers: { ...this.defaultHeaders, ...customHeaders },
    };

    if (data) {
      if (data instanceof FormData) {
        options.body = data;
        delete options.headers['Content-Type'];
      } else {
        options.body = JSON.stringify(data);
      }
    }

    let attempt = 0;
    while (attempt < retries) {
      try {
        return await this._fetchWithTimeout(url, options, timeout);
      } catch (error) {
        if (attempt === retries - 1 || !this._isRetryableError(error)) {
          throw error;
        }
        attempt++;
        console.warn(`Attempt ${attempt} failed, retrying...`);
        await this._delay(1000);
      }
    }
  }

  /**
   * Realiza la solicitud con manejo de timeout.
   * @param {string} url - URL del endpoint
   * @param {object} options - Opciones de la solicitud
   * @param {number} timeout - Tiempo máximo de espera en milisegundos
   * @returns {Promise} - Respuesta de la solicitud
   */
  async _fetchWithTimeout(url, options, timeout) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);
    options.signal = controller.signal;

    try {
      const response = await fetch(url, options);
      clearTimeout(timeoutId);

      if (!response.ok) {
        throw new FetchServiceError(
          `HTTP Error: ${response.status} ${response.statusText}`,
          response.status,
          response.statusText
        );
      }

      const contentType = response.headers.get('Content-Type') || '';
      const isFile = contentType.includes('application/octet-stream') || contentType.includes('image/') || contentType.includes('application/pdf');
      const isJson = contentType.includes('application/json');
      
      if (isJson) {
        return response.json();
      } else if (isFile) {
        const blob = await response.blob();
        return blob;
      } else {
        return response.text();
      }
    } catch (error) {
      clearTimeout(timeoutId);
      console.error('FetchService Error:', error.message);
      if (error.name === 'AbortError') {
        throw new FetchServiceError('Request timed out', null, 'The request exceeded the timeout limit');
      }
      if (error.name === 'TypeError') {
        throw new FetchServiceError('Network error or Invalid URL', null, error.message);
      }
      throw error instanceof FetchServiceError ? error : new FetchServiceError('Unexpected Error', null, error.message);
    }
  }

  /**
   * Verifica si el error es de tipo reintentable (Timeout o Error de Red).
   * @param {Error} error - Error ocurrido
   * @returns {boolean} - Si el error es reintentable
   */
  _isRetryableError(error) {
    return error.name === 'AbortError' || error.name === 'TypeError'; // Timeout o error de red
  }

  /**
   * Retrasa la ejecución por una cantidad de milisegundos.
   * @param {number} ms - Milisegundos a esperar
   * @returns {Promise} - Promesa que se resuelve después del retraso
   */
  _delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  /**
   * Realiza una solicitud GET.
   * @param {string} endpoint - URL del endpoint
   * @param {object} [params={}] - Parámetros de la solicitud
   * @param {object} [headers={}] - Encabezados personalizados
   * @returns {Promise} - Respuesta de la solicitud
   */
  get(endpoint, params = {}, headers = {}) {
    const query = new URLSearchParams(params).toString();
    const urlWithQuery = query ? `${endpoint}?${query}` : endpoint;
    return this.request('GET', urlWithQuery, null, headers);
  }

  /**
   * Realiza una solicitud POST.
   * @param {string} endpoint - URL del endpoint
   * @param {object} data - Datos a enviar
   * @param {object} [headers={}] - Encabezados personalizados
   * @returns {Promise} - Respuesta de la solicitud
   */
  post(endpoint, data, headers = {}) {
    return this.request('POST', endpoint, data, headers);
  }

  /**
   * Realiza una solicitud PUT.
   * @param {string} endpoint - URL del endpoint
   * @param {object} data - Datos a enviar
   * @param {object} [headers={}] - Encabezados personalizados
   * @returns {Promise} - Respuesta de la solicitud
   */
  put(endpoint, data, headers = {}) {
    return this.request('PUT', endpoint, data, headers);
  }

  /**
   * Realiza una solicitud DELETE.
   * @param {string} endpoint - URL del endpoint
   * @param {object} [data=null] - Datos a enviar
   * @param {object} [headers={}] - Encabezados personalizados
   * @returns {Promise} - Respuesta de la solicitud
   */
  delete(endpoint, data = null, headers = {}) {
    return this.request('DELETE', endpoint, data, headers);
  }

  /**
   * Establece un encabezado global.
   * @param {string} key - Clave del encabezado
   * @param {string} value - Valor del encabezado
   */
  setHeader(key, value) {
    this.defaultHeaders[key] = value;
  }

  /**
   * Elimina un encabezado global.
   * @param {string} key - Clave del encabezado
   */
  removeHeader(key) {
    delete this.defaultHeaders[key];
  }

  /**
   * Establece un token de autenticación en los encabezados.
   * @param {string} token - Token de autenticación
   */
  setAuthToken(token) {
    this.setHeader('Authorization', `Bearer ${token}`);
  }

  removeAuthToken() {
    this.removeHeader('Authorization');
  }
}
