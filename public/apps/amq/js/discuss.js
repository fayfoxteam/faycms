window.onload = function () {
    var list = document.getElementById('list');
    var boxs = list.children;
    var timer;

    /*点赞*/
    function praiseBox(el) {
        var myPraise = parseInt(el.getAttribute('my'));
        var oldTotal = parseInt(el.getAttribute('total'));
        var newTotal;
        if (myPraise == 0) {
            newTotal = oldTotal + 1;
            el.setAttribute('total', newTotal);
            el.setAttribute('my', 1);
            el.innerHTML =  ' 取消赞' + '（'+ newTotal + '）';
        }
        else {
            newTotal = oldTotal - 1;
            el.setAttribute('total', newTotal);
            el.setAttribute('my', 0);
            el.innerHTML = (newTotal == 0) ? '赞' + '（' + oldTotal + '）':  ' 赞' + '（'+ newTotal + '）';
        }
        el.style.display = (newTotal == 0) ? '' : 'inline-block'
    }

    /*发评论*/
    function reply(box, el) {
        var commentList = box.getElementsByClassName('comment-list')[0];
        var textarea = box.getElementsByClassName('comment')[0];
        var commentBox = document.createElement('div');
        commentBox.className = 'comment-box clearfix';
        commentBox.innerHTML =
            '<img class="avatr-48 pull-left" src="images/photo_s.jpg" alt=""/>' +
            '<div class="comment-content">' +
                '<p><strong class="comment-user">我</strong><span class="comment-time">刚刚</span></p>' +
                '<p class="txt">' + textarea.value + '</p>' +
                '<p class="info"><a class="comment-praise" href="javascript:;" total0="0" my0="0">赞</a></p>' +
            '</div>';
        commentList.appendChild(commentBox);
        textarea.value = '';
        textarea.onblur();
    }

    /*回复赞*/
    function praiseReply(el) {
        var myPraise0 = parseInt(el.getAttribute('my0'));
        var oldTotal0 = parseInt(el.getAttribute('total0'));
        var newTotal0;
        if (myPraise0 == 0) {
            newTotal0 = oldTotal0 + 1;
            el.setAttribute('total0', newTotal0);
            el.setAttribute('my0', 1);
            el.innerHTML =  ' 取消赞' + '（'+ newTotal0 + '）';
        }
        else {
            newTotal0 = oldTotal0 - 1;
            el.setAttribute('total0', newTotal0);
            el.setAttribute('my0', 0);
            el.innerHTML = (newTotal0 == 0) ? '赞' :  ' 赞' + '（'+ newTotal0 + '）';
        }
        el.style.display = (newTotal0 == 0) ? '' : 'inline-block'
    }


    for (var i = 0; i < boxs.length; i++) {
        //点击
        boxs[i].onclick = function (e) {
            e = e || window.event;
            var el = e.srcElement;
            switch (el.className) {
                //赞
                case 'praise':
                    praiseBox(el);
                    break;

                //按钮橙
                case 'btn0':
                    reply(el.parentNode.parentNode.parentNode, el);
                    break;

                //按钮灰
                case 'btn0 btn-off':
                    clearTimeout(timer);
                    break;

                //赞留言
                case 'comment-praise':
                    praiseReply(el);
                    break;
            }
        }

        //评论
        var textArea = boxs[i].getElementsByClassName('comment')[0];

        //评论获取焦点
        textArea.onfocus = function () {
            this.parentNode.className = 'text-box text-box-on';
            this.value = this.value == '' ? '' : this.value;
            this.onkeyup();
        }

        //评论失去焦点
        textArea.onblur = function () {
            var me = this;
            var val = me.value;
            if (val == '') {
                timer = setTimeout(function () {
                    me.value = '';
                    me.parentNode.className = 'text-box';
                }, 200);
            }
        }

        //评论按键事件
        textArea.onkeyup = function () {
            var val = this.value;
            var len = val.length;
            var els = this.parentNode.children;
            var btn = els[0];
            var word = els[2];
            if (len <=0 || len > 140) {
                btn.className = 'btn0 btn-off';
            }
            else {
                btn.className = 'btn0';
            }
            word.innerHTML = len + '/140';
        }

    }
}

