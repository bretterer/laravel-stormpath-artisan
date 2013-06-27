<?php namespace Lamarus\StormpathArtisan\Stormpath;

class Directory extends Stormpath {

	protected $directories;

	/**
     * Generate a table of directories to display
     *
     * @param Table Helper Set
     * @return table
     */
	function getDirectoryTable($table) {
		$table
			->setHeaders(['', 'Directory Name', 'Description', 'Status', 'Directory ID']);

		// get list of directorylicaiton and ask which to delete
		$response = $this->client->get($this->tenants['href'].'/directories')->send()->json();

		foreach($response['items'] as $key=>$directory) {
			$directoryID = explode('/',$directory['href']);
			$directoryID = end($directoryID);
			$directoryArr[] = array(
										($key+1), 
										$directory['name'],
										$directory['description'], 
										$directory['status'], 
										$directoryID
									);
			$this->directories[$key+1] = array('id'=>$directoryID,'name'=>$directory['name'],'description'=>$directory['description']);
		}

		$table->setRows($directoryArr);

		return $table;

	}

	function enableDirectory($directoryID) {
		$request = $this->client->post("directories/{$directoryID}");
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'status'=>'ENABLED')), 'application/json');

		$response = $request->send();
		if($response->getStatusCode() === 200) {
			return array('messageType'=>'info','message'=>'directory Enabled');
		} else {
			return array('messageType'=>'error','message'=>'Error Enabling directory');
		}
	}

	function disableDirectory($directoryID) {
		$request = $this->client->post("directories/{$directoryID}");
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'status'=>'DISABLED')), 'application/json');

		$response = $request->send();

		if($response->getStatusCode() === 200) {
			return array('messageType'=>'info','message'=>'directory Disabled');
		} else {
			return array('messageType'=>'error','message'=>'Error Disabling directory');
		}
	}

	function deleteDirectory($directoryID) {
		$response = $this->client->delete("directories/{$directoryID}")->send();

		if($response->getStatusCode() === 204) {
			return array('messageType'=>'info','message'=>'directory Deleted');
		} else {
			return array('messageType'=>'error','message'=>'Error Deleting directory');
		}
	}

	function createDirectory($items) {
		$request = $this->client->post('directories');
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'name'=>$items['directoryName'],
			'description'=>$items['directoryDescription'],
			'status'=>$items['enabled'])), 'application/json');

		$response = $request->send();

		if($response->getStatusCode() === 201) {
			return array('messageType'=>'info','message'=>'directory Created');
		} else {
			return array('messageType'=>'error','message'=>'Error Creating directory');
		}
	}

	function modifyDirectory($items) {
		$request = $this->client->post('directories/'.$items['id']);
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'name'=>$items['directoryName'],
			'description'=>$items['directoryDescription'])), 'application/json');

		$response = $request->send();

		if($response->getStatusCode() === 200) {
			return array('messageType'=>'info','message'=>'directory Updated');
		} else {
			return array('messageType'=>'error','message'=>'Error Updating directory');
		}
	}

	function getDirectoriesArray() {
		return $this->directories;
	}
}