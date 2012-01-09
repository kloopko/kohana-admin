<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin media controller
 *
 * @author	Kemal Delalic <kemal.delalic@gmail.com>
 */ 
class Controller_Admin_Media extends Kohana_Controller {

	/**
	 * Action for serving media files with HTTP caching enabled
	 *
	 * @return	Response
	 * @throws	HTTP_Exception_404 in case file doesn't exist
	 */
	public function action_get()
	{
		// Get the filename & extension from requested filenames pathinfo
		$info = pathinfo($this->request->param('file'));
		
		// Extract required infos into the local scope
		list($filename, $ext, $directory) = array_values(Arr::extract($info,
			array('filename','extension','dirname')));
		
		$directory = $directory ? "media/{$directory}" : 'media';
		
		if ($file = Kohana::find_file($directory, $filename, $ext))
		{
			// If the requested file is found inside of the media folder,
			// return the Response before the exception below is thrown
			return $this->response
				->body(file_get_contents($file))
				->headers('cache-control','public, max-age='.Date::WEEK)
				->headers('content-type', File::mime($file))
				->check_cache(NULL, $this->request);
		}
		
		// If there is no return yet, throw a 404 HTTP error
		throw new HTTP_Exception_404('Media file not found: :file', 
			array(':file' => $filename));
	}

}
