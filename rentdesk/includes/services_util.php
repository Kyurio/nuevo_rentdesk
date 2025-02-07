<?php
class ServicesRestful
{
	public function sendPost($url, $data, $customHeaders, $queryParams)
	{
		$datos_login = unserialize($_SESSION["sesion_rd_login"]);
		$datos_user = unserialize($_SESSION["sesion_rd_usuario"]);

		var_dump("datos_login: ", $datos_login);
		var_dump("datos_user: ", $datos_user);

		// Initialize cURL session
		$ch = curl_init();

		if (isset($queryParams)) {
			curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryParams));
		} else {
			curl_setopt($ch, CURLOPT_URL, $url);
		}

		curl_setopt($ch, CURLOPT_USERPWD, $datos_user->token . ":" . $datos_login->contrasena);

		// Set option to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Set the HTTP request method to POST
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

		if (isset($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}

		// Set custom headers
		$headers = [];

		if (isset($customHeaders)) {

			foreach ($customHeaders as $key => $value) {
				$headers[] = $key . ': ' . $value;
			}
		}

		// $headers[] = 'Accept: application/json'; 
		$headers[] = 'Content-Type: application/json';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Execute the cURL session
		// var_dump("curl_exec: ", $ch);

		$response = curl_exec($ch);


		// Close cURL session
		curl_close($ch);

		// Check if response is empty
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}
	
		public function sendPostDirecto($url,$data)
	{
		//url contra la que atacamos
		$ch = curl_init($url);
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		//enviamos tipo de datos que deseamos retornar
		$headers = array('Accept: application/json');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if(!$response) {
			return false;
		}else{
			$replaced = str_replace('\t', '', $response);
			return $replaced;
		}
	}


	public function sendPostNoToken($url, $data)
	{
		//url contra la que atacamos
		$ch = curl_init($url);
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		//enviamos tipo de datos que deseamos retornar
		$headers = array('Accept: application/json');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}

	public function sendPut($url, $data, $customHeaders, $queryParams)
	{

		$datos_login = unserialize($_SESSION["sesion_rd_login"]);
		$datos_user = unserialize($_SESSION["sesion_rd_usuario"]);

		// Initialize cURL session
		$ch = curl_init();

		if (isset($queryParams)) {
			curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryParams));
		} else {
			curl_setopt($ch, CURLOPT_URL, $url);
		}


		curl_setopt($ch, CURLOPT_USERPWD, $datos_user->token . ":" . $datos_login->contrasena);


		// Set option to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Set the HTTP request method to PUT
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

		if (isset($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}

		// Set custom headers
		$headers = [];

		if (isset($customHeaders)) {
			foreach ($customHeaders as $key => $value) {
				$headers[] = $key . ': ' . $value;
			}
		}
		// $headers[] = 'Accept: application/json'; // Assuming you want to include this header always

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Execute the cURL session
		var_dump("curl_exec: ", $ch);

		$response = curl_exec($ch);


		var_dump($response);
		// Close cURL session
		curl_close($ch);

		// Check if response is empty
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}
	
	 public function sendPutRentdesk($url, $data, $customHeaders, $queryParams)
	{

          $datos_login = unserialize($_SESSION["sesion_rd_login"]);
          $datos_user = unserialize($_SESSION["sesion_rd_usuario"]);
      
          // Initialize cURL session
          $ch = curl_init();
      
          if (isset($queryParams)) {
              curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryParams));
          } else {
              curl_setopt($ch, CURLOPT_URL, $url);
          }
      
          curl_setopt($ch, CURLOPT_USERPWD, $datos_user->token . ":" . $datos_login->contrasena);
      
          // Set option to return the transfer as a string
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
          // Set the HTTP request method to PUT
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      
          if (isset($data)) {
              curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          }
      
          // Set custom headers
          $headers = [];
      
          if (isset($customHeaders)) {
              foreach ($customHeaders as $key => $value) {
                  $headers[] = $key . ': ' . $value;
              }
          }
      
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
          // Execute the cURL session
          $response = curl_exec($ch);
      
          // Get response status code
          $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      
          // Close cURL session
          curl_close($ch);
      
          // Check if response is empty

              // Return response along with status code
          return array('status_code' => $statusCode, 'response' => $response);
          
		
	}

	
	private function getHttpStatus($url) {
        $headers = get_headers($url);
        return $headers[0];
    }

	public function sendPutNoToken($url, $data)
	{
		//url contra la que atacamos
		$ch = curl_init($url);
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		//enviamos tipo de datos que deseamos retornar
		$headers = array('Accept: application/json');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);

		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}



	public function sendGet($url, $data, $customHeaders, $queryParams)
	{

		$datos_login = unserialize($_SESSION["sesion_rd_login"]);
		$datos_user = unserialize($_SESSION["sesion_rd_usuario"]);

		// Initialize cURL session
		$ch = curl_init();

		if (isset($queryParams)) {
			curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryParams));
		} else {
			curl_setopt($ch, CURLOPT_URL, $url);
		}


		curl_setopt($ch, CURLOPT_USERPWD, $datos_user->token . ":" . $datos_login->contrasena);


		// Set option to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Set the HTTP request method to GET
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		if (isset($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
		// Set custom headers
		$headers = [];

		if (isset($customHeaders)) {
			foreach ($customHeaders as $key => $value) {
				$headers[] = $key . ': ' . $value;
			}
		}
		$headers[] = 'Accept: application/json'; // Assuming you want to include this header always

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Execute the cURL session
		// var_dump("curl_exec: ", $ch);

		$response = curl_exec($ch);


		// Close cURL session
		curl_close($ch);

		// Check if response is empty
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}

	public function sendGetNoToken($url, $data, $customHeaders = [])
	{


		// Initialize cURL session
		$ch = curl_init();

		if ($data) {
			curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
		} else {
			curl_setopt($ch, CURLOPT_URL, $url);
		}


		// Set option to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Set the HTTP request method to GET
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		// Set custom headers
		$headers = [];
		foreach ($customHeaders as $key => $value) {
			$headers[] = $key . ': ' . $value;
		}
		// $headers[] = 'Accept: application/json'; 
		$headers[] = 'Content-Type: application/json';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Execute the cURL session
		// var_dump("curl_exec: ", $ch);

		$response = curl_exec($ch);


		// Close cURL session
		curl_close($ch);

		// Check if response is empty
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}



	public function sendDelete($url, $data)
	{
		//url contra la que atacamos
		$ch = curl_init($url);
		//a true, obtendremos una respuesta de la url, en otro caso, 
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		//enviamos tipo de datos que deseamos retornar
		$headers = array('Accept: application/json');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}

	public function sendPatch($url, $data, $customHeaders, $queryParams)
	{
		var_dump("ENTRÓ A PATCH: ");

		$datos_login = unserialize($_SESSION["sesion_rd_login"]);
		$datos_user = unserialize($_SESSION["sesion_rd_usuario"]);

		var_dump("datos_login: ", $datos_login);
		var_dump("datos_user: ", $datos_user);

		// Initialize cURL session
		$ch = curl_init();

		if (isset($queryParams)) {
			curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryParams));
		} else {
			curl_setopt($ch, CURLOPT_URL, $url);
		}

		curl_setopt($ch, CURLOPT_USERPWD, $datos_user->token . ":" . $datos_login->contrasena);

		// Set option to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Set the HTTP request method to PATCH
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");

		if (isset($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}

		// Set custom headers
		$headers = [];

		if (isset($customHeaders)) {

			foreach ($customHeaders as $key => $value) {
				$headers[] = $key . ': ' . $value;
			}
		}

		$headers[] = 'Accept: application/json'; // Assuming you want to include this header always

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Execute the cURL session
		// var_dump("curl_exec: ", $ch);

		$response = curl_exec($ch);


		// Close cURL session
		curl_close($ch);

		// Check if response is empty
		if (!$response) {
			return false;
		} else {
			return $response;
		}
	}
}
