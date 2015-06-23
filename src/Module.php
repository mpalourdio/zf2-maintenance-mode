<?php
/*
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace ZfMaintenanceMode;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements InitProviderInterface, ConfigProviderInterface
{
    /**
     * @param  ModuleManagerInterface $moduleManager
     * @throws \Exception
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        if (PHP_SAPI === 'cli') {
            return;
        }

        $rootApplicationPath = realpath(dirname('.'));
        $userConfig          = $this->getUserConfig();

        if (! isset($userConfig['maintenance']['flag_file'])) {
            throw new \Exception(
                'The \'flag_file\' parameter is missing in config/autoload/config/autoload/maintenance{,*.}{global,local}.php'
            );
        }

        if (file_exists($rootApplicationPath . DIRECTORY_SEPARATOR . $userConfig['maintenance']['flag_file'])) {
            if (! isset($userConfig['maintenance']['status_code']) ||
                ! isset($userConfig['maintenance']['message'])
            ) {
                throw new \Exception(
                    'Please fill all the parameters in config/autoload/config/autoload/maintenance{,*.}{global,local}.php'
                );
            }

            $moduleManager->getEventManager()->attach(
                ModuleEvent::EVENT_LOAD_MODULE,
                function (ModuleEvent $moduleEvent) {
                    $moduleEvent->stopPropagation(true);
                },
                PHP_INT_MAX
            );

            $moduleManager->getEventManager()->getSharedManager()->attach(
                Application::class,
                MvcEvent::EVENT_BOOTSTRAP,
                function (MvcEvent $mvcEvent) use ($userConfig) {
                    $response = $mvcEvent->getResponse();
                    $response->setStatusCode($userConfig['maintenance']['status_code']);
                    if($userConfig['maintenance']['custom_file']) {
                         $response->setContent( file_get_contents($userConfig['maintenance']['custom_file']));
                    } else {
                        $response->setContent('<h1>' . $userConfig['maintenance']['message'] . '</h1>');
                    }
                    $mvcEvent->stopPropagation(true);
                },
                PHP_INT_MAX
            );
        }
    }

    /**
     * @return array|mixed|\Traversable
     */
    public function getConfig()
    {
        return require __DIR__ . '/../config/module.config.php';
    }

    /**
     * @return array|mixed|\Traversable
     */
    public function getUserConfig()
    {
        $globPaths   = glob('config/autoload/maintenance{,*.}{global,local}.php', GLOB_BRACE);
        $globCounter = count($globPaths);

        if ($globCounter > 0) {
            return require $globPaths[$globCounter - 1];
        }

        return require __DIR__ . '/../config/maintenance.config.global.php.dist';
    }

    /**
     * Return the console usage for this module
     *
     * @param  Console $console
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            'maintenance enable'  => 'Enable maintenance mode',
            'maintenance disable' => 'Disable maintenance mode',
        ];
    }
}
