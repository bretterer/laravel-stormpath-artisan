<?php namespace Lamarus\StormpathArtisan\Stormpath;

class Application extends Stormpath {

	protected $applications;

	/**
     * Generate a table of applications to display
     *
     * @param Table Helper Set
     * @return table
     */
	function getApplicationsTable($table) {
		$table
			->setHeaders(['', 'Application Name', 'Description', 'Status', 'Applicaiton ID']);

		// get list of applicaiton and ask which to delete
		$response = $this->client->get($this->tenants['href'].'/applications')->send()->json();

		foreach($response['items'] as $key=>$application) {
			$applicationID = explode('/',$application['href']);
			$applicationID = end($applicationID);
			$applicationArr[] = array(
										($key+1), 
										$application['name'],
										$application['description'], 
										$application['status'], 
										$applicationID
									);
			$this->applications[$key+1] = array('id'=>$applicationID,'name'=>$application['name'],'description'=>$application['description']);
		}

		$table->setRows($applicationArr);

		return $table;

	}

	function enableApplication($applicationID) {
		$request = $this->client->post("applications/{$applicationID}");
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'status'=>'ENABLED')), 'application/json');

		$response = $request->send();
		if($response->getStatusCode() === 200) {
			return array('messageType'=>'info','message'=>'Application Enabled');
		} else {
			return array('messageType'=>'error','message'=>'Error Enabling Application');
		}
	}

	function disableApplication($applicationID) {
		$request = $this->client->post("applications/{$applicationID}");
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'status'=>'DISABLED')), 'application/json');

		$response = $request->send();

		if($response->getStatusCode() === 200) {
			return array('messageType'=>'info','message'=>'Application Disabled');
		} else {
			return array('messageType'=>'error','message'=>'Error Disabling Application');
		}
	}

	function deleteApplication($applicationID) {
		$response = $this->client->delete("applications/{$applicationID}")->send();

		if($response->getStatusCode() === 204) {
			return array('messageType'=>'info','message'=>'Application Deleted');
		} else {
			return array('messageType'=>'error','message'=>'Error Deleting Application');
		}
	}

	function createApplication($items) {
		$request = $this->client->post('applications');
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'name'=>$items['appName'],
			'description'=>$items['appDescription'],
			'status'=>$items['enabled'])), 'application/json');

		$response = $request->send();

		if($response->getStatusCode() === 201) {
			return array('messageType'=>'info','message'=>'Application Created');
		} else {
			return array('messageType'=>'error','message'=>'Error Creating Application');
		}
	}

	function modifyApplication($items) {
		$request = $this->client->post('applications/'.$items['id']);
		$request->setHeader('Accept','application/json');
		$request->setBody(json_encode(array(
			'name'=>$items['appName'],
			'description'=>$items['appDescription'])), 'application/json');

		$response = $request->send();

		if($response->getStatusCode() === 200) {
			return array('messageType'=>'info','message'=>'Application Updated');
		} else {
			return array('messageType'=>'error','message'=>'Error Updating Application');
		}
	}

	function getApplicationsArray() {
		return $this->applications;
	}

}