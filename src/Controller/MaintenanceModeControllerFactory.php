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

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MaintenanceModeControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentlocator = $serviceLocator->getServiceLocator();

        if (! isset ($parentlocator->get('Config')['maintenance']['flag_file'])) {
            $defaultModuleConfig = require __DIR__ . '/../../config/maintenance.config.global.php.dist';
            $flagFilePath        = $defaultModuleConfig['maintenance']['flag_file'];
        } else {
            $flagFilePath = $parentlocator->get('Config')['maintenance']['flag_file'];
        }

        return new MaintenanceModeController($flagFilePath);
    }
}
