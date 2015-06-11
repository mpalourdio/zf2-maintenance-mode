<?php
/*
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace ZfMaintenanceMode\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class MaintenanceModeController extends AbstractActionController
{
    /**
     * @param  EventManagerInterface $events
     * @return self
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach(
            'dispatch',
            function ($e) {
                $request = $e->getRequest();
                if (! $request instanceof ConsoleRequest) {
                    throw new \RuntimeException(sprintf(
                        '%s can only be executed in a console environment',
                        __CLASS__
                    ));
                }
            },
            100
        );

        return $this;
    }

    /**
     * @return string
     */
    public function enableAction()
    {
        if (file_exists('config/maintenance.flag')) {
            return "Already in maintenance mode!\n";
        }

        touch('config/maintenance.flag');

        return "You are now in maintenance mode.\n";
    }

    /**
     * @return string
     */
    public function disableAction()
    {
        var_dump(realpath(dirname('.')));
        if (! file_exists('config/maintenance.flag')) {
            return "Maintenance mode was already disabled.\n";
        }

        unlink('config/maintenance.flag');

        return "Maintenance mode is now disabled.\n";
    }
}
