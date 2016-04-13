"use strict";angular.module("sbAdminApp").controller("PostCtrl",function($scope,$stateParams,secureService,$window){var post=secureService.getPost($stateParams.postId);$scope.page=1,$scope.data={},$scope.data.posts=[],$scope.availableOptions=[{post_type:2,value:"Has"},{post_type:1,value:"Need"}],post.then(function(data){$scope.post=data});var all_posts=secureService.getAllPosts($scope.page);all_posts.then(function(data){$scope.data=data}),$scope.refresh=function(){all_posts=secureService.getAllPosts($scope.page),all_posts.then(function(data){$scope.data=data})},$scope.updatePost=function(pos){pos.post_type="Need"==pos.post_type?1:2,$.ajax({type:"POST",url:"http://54.213.179.207/homerapi/admin/posts/"+pos.id,headers:{Authorization:localStorage.getItem("Token")},data:pos}).done(function(data){$scope.post=data})},$scope.deletePost=function(post){$window.confirm("Are you absolutely sure you want to delete?")&&$.ajax({type:"DELETE",url:"http://54.213.179.207/homerapi/admin/posts/"+post.id,headers:{Authorization:localStorage.getItem("Token")}}).done(function(data){window.location.reload()})},$scope.searchPost=function(q){var Searchposts=secureService.searchPost(q);Searchposts.then(function(result){$scope.posts=result})}});