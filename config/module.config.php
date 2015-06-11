<?php
/*
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use ZfMaintenanceMode\Controller\MaintenanceModeController;

return [
    'controllers' => [
        'invokables' => [
            MaintenanceModeController::class => MaintenanceModeController::class,
        ],
    ],
    'console'     => [
        'router' => [
            'routes' => [
                'maintenance-disable' => [
                    'options' => [
                        'route'    => 'maintenance disable',
                        'defaults' => [
                            'controller' => MaintenanceModeController::class,
                            'action'     => 'disable',
                        ],
                    ],
                ],
                'maintenance-enable'  => [
                    'options' => [
                        'route'    => 'maintenance enable',
                        'defaults' => [
                            'controller' => MaintenanceModeController::class,
                            'action'     => 'enable',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
