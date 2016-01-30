
angular.module('iceberg.insertHTMLToolService', [])
    .service('ieservice', ['$rootScope', 'ieextensions', function (scope, ieextensions) {
        var isInsertEditorMouseOver = false, lastSavedSelectionData = null;
        this.appendInsertHTMLMenu = function (element) {
            //create empty insert menu
            console.log('Append Blank Insert Menu');
            $('#insertEditor').append('<button class="inlineToolTip-btn control"><span class="glyphicon glyphicon-plus-sign"></span></button>');
            $('#insertEditor .inlineToolTip-btn')
                .click(function () {
                    console.log('clicked plus!');
                    $('#insertEditor button').show();
                    ieextensions.test();
                });
            $('#insertEditor').append('<button class="btn btn-default video-btn">Video</button>');
            $('#insertEditor button.video-btn')
                .hide()
                .click(function () {
                    $('#insertEditor button:not(.inlineToolTip-btn)').hide();
                    $('#insertEditor').hide();
                    getContenteditableFieldValue('Paste youtube URL here and enter');
                });
            $('#insertEditor button').mouseover(function () {
                isInsertEditorMouseOver = true;
            });
            $('#insertEditor button').mouseout(function () {
                isInsertEditorMouseOver = false;
            })
        }
        getContenteditableFieldValue = function (placeholder) {
            console.log('getValue()');
            var sel = lastSavedSelectionData;
            // var range = sel.getRangeAt(0);
            //placeholder
            insertHtmlAfterSelection(sel, ((placeholder) ? '<span id="default-value">' + placeholder + '</span>' : '<span id="default-value">Type here and enter</span>'));
            /*$('#default-value').on('click', (function(){
             console.log('clicked default-value');
             $('#default-value')
             .text(' ')
             .unbind('click')
             .focus();
             }));*/
        }

        insertHtmlAfterSelection = function (sel, html) {
            var range, expandedSelRange, node;
            if (sel) {
                if (sel.getRangeAt && sel.rangeCount) {
                    range = sel.getRangeAt(0);
                    expandedSelRange = range.cloneRange();
                    range.collapse(false);

                    // Range.createContextualFragment() would be useful here but is
                    // non-standard and not supported in all browsers (IE9, for one)
                    var el = document.createElement("div");
                    el.innerHTML = html;
                    var frag = document.createDocumentFragment(), node, lastNode;
                    while ((node = el.firstChild)) {
                        lastNode = frag.appendChild(node);
                    }
                    range.insertNode(frag);
                    // Preserve the selection
                    /*
                     if (lastNode) {
                     expandedSelRange.setEndAfter(lastNode);
                     sel.removeAllRanges();
                     sel.addRange(expandedSelRange);
                     sel.removeAllRanges();
                     }*/
                }
            }
            /* else if (document.selection && document.selection.createRange) { //Out of Update
             range = document.selection.createRange();
             expandedSelRange = range.duplicate();
             range.collapse(false);
             range.pasteHTML(html);
             expandedSelRange.setEndPoint("EndToEnd", range);
             expandedSelRange.select();
             } */
        }
        this.saveSelection = function () {
            console.log('Saved selection');
            lastSavedSelectionData = window.getSelection();
            ;
        }
        this.getSavedSelection = function () {
            console.log('Get saved selection');
            return lastSavedSelectionData;
        }
        this.isAttachmentAvailable = function (e) {
            var target = angular.element(e.target);
            selection = window.getSelection();
            if (selection['focusNode']['childElementCount'] > 1 || selection['focusNode']['nodeValue'] != null || selection['focusNode']['childNodes'][0]['nodeName'] != 'BR') {
                //console.log('Tag: ', e.target.textContent, e.target.tagName);
                this.hideAttachmentMenu();
            } else {
                this.saveSelection();
                //console.log('Tag: ', e.target.textContent, e.target.tagName);
                this.showAttachmentMenu();
            }
        }
        this.showAttachmentMenu = function () {
            var sel = window.getSelection(),
                posY = sel.getRangeAt(0)['commonAncestorContainer']['offsetHeight'] + window.getSelection().getRangeAt(0)['commonAncestorContainer']['offsetTop'] - 100,
                posX = sel.getRangeAt(0)['commonAncestorContainer']['offsetLeft'];
            scope.$apply(
                function () {
                    scope.insertEditorStyle = {'top': posY + 'px', 'left': posX + 'px', 'display': 'block'};
                }
            )
            console.info('available attachment', sel, posY, posX);
        }
        this.hideAttachmentMenu = function () {
            console.info('not available attachment ', isInsertEditorMouseOver);
            if (isInsertEditorMouseOver == false) {
                scope.$apply(
                    function () {
                        scope.insertEditorStyle = {'display': 'none'};
                    }
                )
            }
        }
    }]);