<?php

namespace Fadllabanie\EnvEditor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Artisan;

class EnvEditorController extends Controller
{
    /**
     * Display the .env file content in a form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $envData = $this->readEnvFile();
        $envArray = $this->parseEnv($envData);

        return view('env-editor::edit', compact('envArray'));
    }

    /**
     * Update the .env file with the submitted form data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $envData = $request->input('env', []);

        $this->updateEnvFile($envData);
        $this->clearCacheAndReloadEnv();

        // Forget authentication and redirect to login
        session()->forget('env_editor_authenticated');
        return redirect()->route('env.login')->with('success', '.env file updated successfully!');
    }

    /**
     * Read the .env file content.
     *
     * @return string
     */
    private function readEnvFile()
    {
        return File::get(base_path('.env'));
    }

    /**
     * Parse the .env file content into an array.
     *
     * @param  string  $envData
     * @return array
     */
    private function parseEnv($envData)
    {
        $lines = explode("\n", $envData);
        $envArray = [];

        foreach ($lines as $line) {
            if ($this->isValidEnvLine($line)) {
                list($key, $value) = explode('=', $line, 2);
                $envArray[trim($key)] = trim($value);
            }
        }

        return $envArray;
    }

    /**
     * Validate if the line is a valid .env entry.
     *
     * @param  string  $line
     * @return bool
     */
    private function isValidEnvLine($line)
    {
        return !empty($line) && strpos($line, '=') !== false;
    }

    /**
     * Update the .env file with the given data.
     *
     * @param  array  $envData
     * @return void
     */
    private function updateEnvFile(array $envData)
    {
        $envString = $this->buildEnvString($envData);
        File::put(base_path('.env'), $envString);
    }

    /**
     * Build the .env file content string from the array.
     *
     * @param  array  $envData
     * @return string
     */
    private function buildEnvString(array $envData)
    {
        $envString = '';

        foreach ($envData as $key => $value) {
            $envString .= "$key=$value\n";
        }

        return $envString;
    }

    /**
     * Clear the cache and reload the .env configuration.
     *
     * @return void
     */
    private function clearCacheAndReloadEnv()
    {
        Artisan::call('config:clear');

        if (file_exists(base_path('.env'))) {
            $dotenv = Dotenv::createImmutable(base_path());
            $dotenv->load();
        }
    }
}
