!function($) {
    "use strict";
    var CodeEditor = function() {};
    CodeEditor.prototype.getSelectedRange = function(editor) {
        return { from: editor.getCursor(true), to: editor.getCursor(false) };
    },
    CodeEditor.prototype.autoFormatSelection = function(editor) {
        var range = this.getSelectedRange(editor);
        editor.autoFormatRange(range.from, range.to);
    },
    CodeEditor.prototype.commentSelection = function(isComment, editor) {
        var range = this.getSelectedRange(editor);
        editor.commentRange(isComment, range.from, range.to);
    },
    CodeEditor.prototype.init = function() {
        var $this = this;
        //init plugin
        CodeMirror.fromTextArea(document.getElementById("customCss"), {
            mode: "css",
            theme: 'material',
            lineNumbers: true
        });
        CodeMirror.fromTextArea(document.getElementById("customJs"), {
            mode: "javascript",
            theme: 'material',
            lineNumbers: true
        });
    },
    //init
    $.CodeEditor = new CodeEditor, $.CodeEditor.Constructor = CodeEditor
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.CodeEditor.init()
}(window.jQuery);
