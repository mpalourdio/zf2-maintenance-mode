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
use Zend\Mvc\MvcEvent;

class MaintenanceModeController extends AbstractActionController
{
    private $flagFilePath;

    /**
     * @param string $flagFilePath
     */
    public function __construct($flagFilePath)
    {
        $this->flagFilePath = $flagFilePath;
    }

    /**
     * @param  EventManagerInterface $events
     * @return self
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach(
            MvcEvent::EVENT_DISPATCH,
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
        if (file_exists($this->flagFilePath)) {
            return "Already in maintenance mode!\n";
        }

        touch($this->flagFilePath);

        return "You are now in maintenance mode.\n";
    }

    /**
     * @return string
     */
    public function disableAction()
    {
        if (! file_exists($this->flagFilePath)) {
            return "Maintenance mode was already disabled.\n";
        }

        unlink($this->flagFilePath);

        return "Maintenance mode is now disabled.\n";
    }
}
