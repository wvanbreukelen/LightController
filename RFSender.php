<?php

/**
 * The RFSender can be used to run specific codes on a 433 MHz transmitter. By example, you can control AC's and RF outlet
 */
class RFSender
{
	/**
	 * Construct a new RF sender instance
	 * @param array
	 */
	public function __construct(array $config = array())
	{
		$this->setConfig($config);
	}

	/**
	 * Set the current active device
	 * @param The lamp identifier to use
	 * @param The state that you wish the light will be setted to
	 * @param Amount of rounds that the code have to been sended, please to not use more that 4 rounds.
	 * @param Override the rounds protection
	 */
	public function setLamp($lamp, $state, $rounds = 2, $override = false)
	{
		if ($rounds > 4 || !is_int($rounds))
		{
			if (!$override)
			{
				throw new Exception("Blocked RF request. Such kind of rounds can overflow the target device, please decrease it's value!");

				return false;
			}
		}

		return $this->sendRFCode($this->grabCorrectCode($lamp, $state), $rounds);
	}

	/**
	 * Send a radio frequency code
	 * @param  The code that have to been send
	 * @param  Amount of rounds that the code have to been sended
	 * @return The shell command output
	 */
	public function sendRFCode($code, $rounds)
	{
		$config = $this->getConfig();

		if (isset($config['retryRounds']))
		{
			$rounds = $config['retryRounds'];
		}
		
		if (!isset($config['utils']))
		{
			throw new Exception("Cannot send RF code, utils path is missing!");
		}

		while ($rounds > 0)
		{
			$output = $this->runCommand("/home/pi/" . $config['utils'] . " " . $code . " 2>&1");

			$rounds = $rounds - 1;
		}

		return $output;
	}

	/**
	 * Set the config array
	 * @param The config array
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}

	/**
	 * Get the config array
	 * @return array The config
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Run a shell command
	 * @param  The command to run
	 * @return The shell output
	 */
	protected function runCommand($command)
	{
		$exec = shell_exec($command);

		return $exec;
	}

	/**
	 * Grabs the correct code out of the config array
	 * @param  The lamp to use
	 * @param  The state that you wish the light will be setted to
	 * @return mixed
	 */
	protected function grabCorrectCode($lamp, $state)
	{
		$config = $this->getConfig();

		if (isset($config['lamps'][$lamp][$state]))
		{
			return $config['lamps'][$lamp][$state];
		}

		throw new Exception("Cannot grab RF code for " . $lamp . " lamp with state " . $state);
	}
}
