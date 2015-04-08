var riders = [
    {
        name: "Valentino Rossi",
        number: 46,
        team: "Movistar Yamaha MotoGP",
        nation: 'ita',
        height: 182,
        weight: 65,
        city: "Urbino"
    },
]

var controllerManager = angular.module('controllers', []);

controllerManager.controller('ridersListController', function($scope){
    $scope.riders = riders;
});

controllerManager.controller('riderDetailsController', function($scope, $routeParams){
    $scope.rider = riders.filter(function(rider){
        return rider.number == $routeParams.number;
    })[0];
});
