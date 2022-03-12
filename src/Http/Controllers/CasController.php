<?php
/******************************************************
 * Copyright (c) 2022 Micorksen.
 * 
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * 
 ******************************************************/

namespace Micorksen\CasServer\Http\Controllers;

use Micorksen\CasServer\Models\Authentication;
use Micorksen\CasServer\Models\Login;
use Micorksen\CasServer\Models\Ticket;
use Micorksen\CasServer\Models\PortalUsers;
use Micorksen\CasServer\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class CasController extends Controller{
    /**
     * Initialize the controller.
     * @param Request $request
     * @return void
    **/
    public function __construct(Request $request){
        if(config("cas-server.disableNonSSL", false) && !$request->secure()) throw new \Exception("Request is not in SSL.");
        if(!session()->isStarted()) throw new \Exception("Sessions are required for CAS Server. Please re-enable the session middleware.");
    }

    /**
     * Get index page.
     * @return Response
    **/
    public function getIndex(){ return redirect("/login"); }

    /**
     * Get login.
     * @param Request $request
     * @param Service $service
     * @param Authentication $authentication
     * @param Ticket $ticket
     * @return Response
    **/
    public function getLogin(Request $request, Service $service, Authentication $authentication, Ticket $ticket){
        $auth = $authentication->loggedIn();
        return $request->secure() && $auth ? $this->validLogin($request, $auth, $service, $ticket, false) : $this->loginPage($service, $request->input("service"), "", $request->secure());
    }

    /**
     * Get login page.
     * @param Service $serviceModel
     * @param string $service
     * @param string $error
     * @param boolean $secure
     * @return Response
    **/
    private function loginPage(Service $serviceModel, $service = "", $error = "", $secure = false){
        $serviceObject = false;
        return $service && !$serviceModel->validate($service) ? $this->serviceError() : view("cas-server::login", compact("service", "error", "secure", "serviceObject"));
    }

    /**
     * Get service error.
     * @return Response 
    **/
    private function serviceError(){
        return view("cas-server::error", [
            "title" => "Application not authorized",
            "description" => "The application you attempted to authenticate to is not authorized to use CAS."
        ]);
    }

    /**
     * Check if the login is valid.
     * @param Request $request
     * @param Authentication $authentication
     * @param Service $service
     * @param Ticket $ticket
     * @param boolean $renew
     * @return Response
    **/
    private function validLogin(Request $request, Authentication $authentication, Service $service, Ticket $ticket, $renew){
        if($request->has("service")){
            $ser = $request->input("service");
            return !$service->validate($ser) ? $this->serviceError() : redirect($service->redirect($ser, $ticket));
        }

        return view("cas-server::success", [ "user" => $authentication->username ]);
    }

    /**
     * Login user.
     * @param Request $request
     * @param Service $service
     * @param Login $login
     * @param Authentication $authentication
     * @param Ticket $ticket
     * @return Response
    **/
    public function postLogin(Request $request, Service $service, Login $login, Authentication $authentication, Ticket $ticket){
        $user = $request->input("username");
        if($login->validate($user, $request->ip(), $request->input("password"))){
            $auth = $authentication->login($user, $login->userAttributes($user), $request->secure());
            return $this->validLogin($request, $auth, $service, $ticket, true);
        } else return $this->loginPage($service, $request->input("service"), "Invalid Login", $request->secure());
    }

    /**
     * This is a CAS 1.0 request.
     * @param Request $request
     * @param Ticket $ticket
     * @return string
    **/
    public function getValidate(Request $request, Ticket $ticket){
        $valid = $this->validateTicket($request, $ticket);
        return (is_object($valid) ? "yes" : "no") . "\n";
    }

    /**
     * Validate ticket.
     * @param Request $request
     * @param Ticket $casTicket
     * @return boolean
     * @return string
    **/
    private function validateTicket(Request $request, Ticket $casTicket){
        $ticket = $request->input("ticket");
        $renew = filter_var($request->input("renew", false), FILTER_VALIDATE_BOOLEAN);
        try{ return $casTicket->validate($ticket, $request->input("service"), $renew); }
        catch(\Exception $exception){ return "INTERNAL_ERROR"; }
    }

    /**
     * Validate CAS 3.0.
     * @param Request $request
     * @param Ticket $ticket
     * @param boolean $attributes
     * @return Response
    **/
    public function getServiceValidate3(Request $request, Ticket $ticket, $attributes = true){
        $service = new Service();
        $valid = $this->validateTicket($request, $ticket);

        if(is_object($valid)){
            $response = [
                "serviceResponse" => [
                    "authenticationSuccess" => [
                        "user" => $valid->authentication->username,
                        "proxyGrantingTicket" => null
                    ]
                ]
            ];

            if($attributes) $response["serviceResponse"]["authenticationSuccess"]["attributes"] = $service->attributes($request->input("service"), $valid->authentication->attributeJson);
        } else{
            $response = [
                "serviceResponse" => [
                    "authenticationFailure" => [
                        "code" => $valid,
                        "description" => "Ticket " . $request->input("ticket") . " not recognized."
                    ]
                ]
            ];
        }

        return strtolower($request->input("format", "XML")) === "json" ? $response : response()->view("cas-server-xml::ticket_xml", $response)->header("Content-Type", "text/xml");
    }

    /**
     * Validate CAS 2.0.
     * @param Request $request
     * @param Ticket $ticket
     * @return string
    **/
    public function getServiceValidate(Request $request, Ticket $ticket){ return $this->getServiceValidate3($request, $ticket, false); }

    /**
     * Disconnect user.
     * @param Request $request
     * @param Service $service
     * @param Authentication $authentication
     * @return Response
    **/
    public function getLogout(Request $request, Service $service, Authentication $authentication){
        $authentication->logout();
        $ser = $service->logoutRedirect($request->input("service"));
        return $ser !== false ? redirect($ser) : view("cas-server::logout");
    }
}
