!function($){$(function(){var e=soliloquy_editor_frame=!1,l=function(l){l.preventDefault(),$(".soliloquy-default-ui .selected").removeClass("details selected"),$(".soliloquy-default-ui").appendTo(".soliloquy-default-ui-wrapper").hide(),e=soliloquy_editor_frame=!1};$(document).on("click",".soliloquy-choose-slider, .soliloquy-modal-trigger",function(o){o.preventDefault(),e=o.target,soliloquy_editor_frame=!0,$(".soliloquy-default-ui").appendTo("body").show(),$(document).on("click",".media-modal-close, .media-modal-backdrop, .soliloquy-cancel-insertion",l),$(document).on("keydown",function(e){27==e.keyCode&&soliloquy_editor_frame&&l(e)})}),$(document).on("click",".soliloquy-default-ui .thumbnail, .soliloquy-default-ui .check, .soliloquy-default-ui .media-modal-icon",function(e){e.preventDefault(),$(this).parent().parent().hasClass("selected")?($(this).parent().parent().removeClass("details selected"),$(".soliloquy-insert-slider").attr("disabled","disabled")):($(this).parent().parent().parent().find(".selected").removeClass("details selected"),$(this).parent().parent().addClass("details selected"),$(".soliloquy-insert-slider").removeAttr("disabled"))}),$(document).on("click",".soliloquy-default-ui .check",function(e){e.preventDefault(),$(this).parent().parent().removeClass("details selected"),$(".soliloquy-insert-slider").attr("disabled","disabled")}),$(document).on("click",".soliloquy-default-ui .soliloquy-insert-slider",function(o){if(o.preventDefault(),$(e).hasClass("soliloquy-choose-slider"))wp.media.editor.insert('[soliloquy id="'+$(".soliloquy-default-ui .selected").data("soliloquy-id")+'"]');else{var i={action:"soliloquy_load_slider_data",id:$(".soliloquy-default-ui:first .selected").data("soliloquy-id")};$.post(ajaxurl,i,function(e){$(document).trigger({type:"soliloquySliderModalData",slider:e}),l(o)},"json")}l(o)})})}(jQuery);