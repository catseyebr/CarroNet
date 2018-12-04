<?php
    require_once WS_PATH . 'xml_handler/movida.php';
    require_once WS_PATH . 'xml_handler/avis.php';
    require_once WS_PATH . 'xml_handler/avisus.php';
    require_once WS_PATH . 'xml_handler/budget.php';
    require_once WS_PATH . 'xml_handler/gmc.php';
    require_once WS_PATH . 'xml_handler/thrifty.php';
    require_once WS_PATH . 'xml_handler/unidas.php';
    require_once WS_PATH . 'xml_handler/holiday.php';
    require_once WS_PATH . 'xml_handler/rdcars.php';
    require_once WS_PATH . 'xml_handler/braspag.php';
    require_once WS_PATH . 'xml_handler/cmnet.php';
    require_once WS_PATH . 'xml_handler/seguroviage.php';
    require_once WS_PATH . 'xml_handler/layum.php';
    require_once WS_PATH . 'xml_handler/localiza.php';
    require_once WS_PATH . 'xml_handler/alamo.php';
    require_once WS_PATH . 'xml_handler/foco.php';
    require_once WS_PATH . 'xml_handler/cielo.php';
    require_once WS_PATH . 'xml_handler/yes.php';
    require_once WS_PATH . 'xml_handler/hertz.php';
    require_once WS_PATH . 'xml_handler/hertzbr.php';
    require_once WS_PATH . 'xml_handler/fleetmax.php';
    require_once WS_PATH . 'xml_handler/nacional.php';
    require_once WS_PATH . 'xml_handler/maggi.php';
    require_once WS_PATH . 'xml_handler/jimpisoft.php';
    require_once WS_PATH . 'xml_handler/exception.php';

    Class XML_Handler_Factory
    {
        public static function create($name, $received, $request, $dados)
        {
            $r = null;
            if ($name == 'movida') {
                $r = new XML_Handler_Movida($received, $request, $dados);
            } else {
                if ($name == 'avis') {
                    $r = new XML_Handler_Avis($received, $request, $dados);
                } else {
                    if ($name == 'avisus') {
                        $r = new XML_Handler_Avisus($received, $request);
                    } else {
                        if ($name == 'budget') {
                            $r = new XML_Handler_Budget($received, $request);
                        } else {
                            if ($name == 'gmc') {
                                $r = new XML_Handler_Gmc($received, $request);
                            } else {
                                if ($name == 'thrifty') {
                                    $r = new XML_Handler_Thrifty($received, $request);
                                } else {
                                    if ($name == 'unidas') {
                                        $r = new XML_Handler_Unidas($received, $request, $dados);
                                    } else {
                                        if ($name == 'holiday') {
                                            $r = new XML_Handler_Holiday($received, $request);
                                        } else {
                                            if ($name == 'rdcars') {
                                                $r = new XML_Handler_Rdcars($received, $request);
                                            } else {
                                                if ($name == 'braspag') {
                                                    $r = new XML_Handler_Braspag($received, $request);
                                                } else {
                                                    if ($name == 'cmnet') {
                                                        $r = new XML_Handler_Cmnet($received, $request);
                                                    } else {
                                                        if ($name == 'seguroviage') {
                                                            $r = new XML_Handler_Seguroviage($received, $request);
                                                        } else {
                                                            if ($name == 'layum') {
                                                                $r = new XML_Handler_Layum($received, $request);
                                                            } else {
                                                                if ($name == 'localiza') {
                                                                    $r = new XML_Handler_Localiza($received, $request, $dados);
                                                                } else {
                                                                    if ($name == 'alamo') {
                                                                        $r = new XML_Handler_Alamo($received, $request, $dados);
                                                                    } else {
                                                                        if ($name == 'foco') {
                                                                            $r = new XML_Handler_Foco($received,
                                                                                $request);
                                                                        } else {
                                                                            if ($name == 'cielo') {
                                                                                $r = new XML_Handler_Cielo($received,
                                                                                    $request);
                                                                            } else {
                                                                                if ($name == 'yes') {
                                                                                    $r = new XML_Handler_Yes($received,
                                                                                        $request);
                                                                                } else {
                                                                                    if ($name == 'hertz') {
                                                                                        $r = new XML_Handler_Hertz($received,
                                                                                            $request, $dados);
                                                                                    } else {
                                                                                        if ($name == 'hertzbr') {
                                                                                            $r = new XML_Handler_Hertzbr($received,
                                                                                                $request, $dados);
                                                                                        } else {
                                                                                            if ($name == 'fleetmax') {
                                                                                                $r = new XML_Handler_Fleetmax($received,
                                                                                                    $request);
                                                                                            } else {
                                                                                                if ($name == 'nacional') {
                                                                                                    $r = new XML_Handler_Nacional($received,
                                                                                                        $request);
                                                                                                } else {
                                                                                                    if ($name == 'maggi') {
                                                                                                        $r = new XML_Handler_Maggi($received,
                                                                                                            $request);
                                                                                                    } else {
                                                                                                        if ($name == 'jimpisoft') {
                                                                                                            $r = new XML_Handler_JImpisoft($received,
                                                                                                                $request);
                                                                                                        } else {
                                                                                                            throw new XML_Handler_Exception(XML_Handler_Exception::NO_XML_FACTORY);
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
                return XML_Handler_Factory::create($func_args[0], $func_args[1]);
            } else {
                throw new XML_Handler_Exception(XML_Handler_Exception::NO_METHOD);
            }
        }
    }
