"use strict";angular.module("sbAdminApp").controller("sessionCtrl",function($scope,$position,secureService,$location){$scope.login=function(user){secureService.login(user)},"/logout"==$location.url()&&(localStorage.clear(),$location.url("/login"))});