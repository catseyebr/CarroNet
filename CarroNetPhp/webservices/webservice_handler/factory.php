<?php
    require_once WS_PATH . 'webservice_handler/movida.php';
    require_once WS_PATH . 'webservice_handler/avis.php';
    require_once WS_PATH . 'webservice_handler/avisus.php';
    require_once WS_PATH . 'webservice_handler/budget.php';
    require_once WS_PATH . 'webservice_handler/gmc.php';
    require_once WS_PATH . 'webservice_handler/thrifty.php';
    require_once WS_PATH . 'webservice_handler/unidas.php';
    require_once WS_PATH . 'webservice_handler/holiday.php';
    require_once WS_PATH . 'webservice_handler/rdcars.php';
    require_once WS_PATH . 'webservice_handler/braspag.php';
    require_once WS_PATH . 'webservice_handler/cmnet.php';
    require_once WS_PATH . 'webservice_handler/seguroviage.php';
    require_once WS_PATH . 'webservice_handler/layum.php';
    require_once WS_PATH . 'webservice_handler/localiza.php';
    require_once WS_PATH . 'webservice_handler/alamo.php';
    require_once WS_PATH . 'webservice_handler/foco.php';
    require_once WS_PATH . 'webservice_handler/cielo.php';
    require_once WS_PATH . 'webservice_handler/yes.php';
    require_once WS_PATH . 'webservice_handler/hertz.php';
    require_once WS_PATH . 'webservice_handler/hertzbr.php';
    require_once WS_PATH . 'webservice_handler/fleetmax.php';
    require_once WS_PATH . 'webservice_handler/nacional.php';
    require_once WS_PATH . 'webservice_handler/maggi.php';
    require_once WS_PATH . 'webservice_handler/jimpisoft.php';
    require_once WS_PATH . 'webservice_handler/exception.php';
    
    Class WebService_Handler_Factory
    {
        public static function create($name)
        {
            $r = null;
            if ($name == 'movida') {
                $r = new WebService_Handler_Movida();
            } else {
                if ($name == 'avis') {
                    $r = new WebService_Handler_Avis();
                } else {
                    if ($name == 'avisus') {
                        $r = new WebService_Handler_Avisus();
                    } else {
                        if ($name == 'budget') {
                            $r = new WebService_Handler_Budget();
                        } else {
                            if ($name == 'gmc') {
                                $r = new WebService_Handler_Gmc();
                            } else {
                                if ($name == 'thrifty') {
                                    $r = new WebService_Handler_Thrifty();
                                } else {
                                    if ($name == 'unidas') {
                                        $r = new WebService_Handler_Unidas();
                                    } else {
                                        if ($name == 'holiday') {
                                            $r = new WebService_Handler_Holiday();
                                        } else {
                                            if ($name == 'rdcars') {
                                                $r = new WebService_Handler_Rdcars();
                                            } else {
                                                if ($name == 'braspag') {
                                                    $r = new WebService_Handler_Braspag();
                                                } else {
                                                    if ($name == 'cmnet') {
                                                        $r = new WebService_Handler_Cmnet();
                                                    } else {
                                                        if ($name == 'seguroviage') {
                                                            $r = new WebService_Handler_Seguroviage();
                                                        } else {
                                                            if ($name == 'layum') {
                                                                $r = new WebService_Handler_Layum();
                                                            } else {
                                                                if ($name == 'localiza') {
                                                                    $r = new WebService_Handler_Localiza();
                                                                } else {
                                                                    if ($name == 'alamo') {
                                                                        $r = new WebService_Handler_Alamo();
                                                                    } else {
                                                                        if ($name == 'foco') {
                                                                            $r = new WebService_Handler_Foco();
                                                                        } else {
                                                                            if ($name == 'cielo') {
                                                                                $r = new WebService_Handler_Cielo();
                                                                            } else {
                                                                                if ($name == 'hertz') {
                                                                                    $r = new WebService_Handler_Hertz();
                                                                                } else {
                                                                                    if ($name == 'yes') {
                                                                                        $r = new WebService_Handler_Yes();
                                                                                    } else {
                                                                                        if ($name == 'hertzbr') {
                                                                                            $r = new WebService_Handler_Hertzbr();
                                                                                        } else {
                                                                                            if ($name == 'fleetmax') {
                                                                                                $r = new WebService_Handler_Fleetmax();
                                                                                            } else {
                                                                                                if ($name == 'nacional') {
                                                                                                    $r = new WebService_Handler_Nacional();
                                                                                                } else {
                                                                                                    if ($name == 'maggi') {
                                                                                                        $r = new WebService_Handler_Maggi();
                                                                                                    } else {
                                                                                                        if ($name == 'jimpisoft') {
                                                                                                            $r = new WebService_Handler_Jimpisoft();
                                                                                                        } else {
                                                                                                            throw new WebService_Handler_Exception(WebService_Handler_Exception::NO_WS_FACTORY);
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $r;
        }
        
        public function __call($func_name, $func_args)
        {
            if ($func_name == 'create') {
                return WebService_Handler_Factory::create($func_args[0], $func_args[1]);
            } else {
                throw new WebService_Handler_Exception(WebService_Handler_Exception::NO_METHOD);
            }
        }
    }