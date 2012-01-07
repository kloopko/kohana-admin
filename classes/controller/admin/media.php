<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Media extends Controller_Admin {

	public function action_get()
	{
		// Get the filename & extension from requested filenames pathinfo
		$info = pathinfo($this->request->param('file'));
		
		list($filename, $ext, $directory) = Arr::values($info,
			array('filename','extension','dirname'));
			
		$directory = $directory ? "media/{$directory}" : 'media';
		
		if ($file = Kohana::find_file($directory, $filename, $ext ?: EXT))
		{
			return $this->response->body(file_get_contents($file))
				->headers('cache-control','public, max-age='.Date::WEEK)
				->headers('content-type', File::mime($file))
				->check_cache(NULL, $this->request);
		}
		
		throw new HTTP_Exception_404('Media file not found: :file', 
			array(':file' => $filename));
	}

}
