define([
        "jquery"
    ],
    function($) {
        "use strict";

        $(document).ready(function($){
            $(".level-2").click(function () {
                $(".item-menu2.parent.level-1").addClass("active");
            });
            $(".admin__menu .action-close").click(function () {
                $(".item-menu2.parent.level-1").removeClass("active");
            });
        });
    });
