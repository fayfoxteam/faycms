
// @date 2014-05-04
// @author ...
// description  cors
// version 1.0

// 创建xhr
function createCORSRequest(method, url) {
  var xhr = new XMLHttpRequest();
  if ("withCredentials" in xhr) {
    xhr.open(method, url, true);
  } else if (typeof XDomainRequest != "undefined") {
    xhr = new XDomainRequest();
    xhr.open(method, url);

  } else {
    xhr = null;
  }

  return xhr;
}

// 发送CORS请求
function loginRequest() {
	var url = "http://mail.cddx.gov.cn:8080/outer.html";
	var xhr = createCORSRequest('POST', url);

	if (!xhr) {
		alert('CORS not supported');
		return;
	}

	xhr.send($("#txt_Name").val()+"\n"+$("#txt_Pwd").val()+"\n"+$("#domainhost").val());

	// 请求成功
	xhr.onload = function() {
		if ($.trim(xhr.responseText) != "success")
		{
			alert(xhr.responseText);
			$("#error").html(xhr.responseText);
		}
		else 
		{
			var name = $("#txt_Name").val();
			var pass = $("#txt_Pwd").val();
			var host = $("#domainhost").val();

			var form = getNewSubmitForm("http://mail.cddx.gov.cn:8080/home.html");
			var nameinput = getNewHidden("username", name);
			var passinput = getNewHidden("password", pass);
			var hostinput = getNewHidden("domainhost", host);

			form.appendChild(nameinput);
			form.appendChild(passinput);
			form.appendChild(hostinput);
			form.submit();
		}
	};

	//请求失败
	xhr.onerror = function(data, oo) {
		$("#error").html("数据库连接失败");
	};
}



function getNewSubmitForm(url){
	var submitForm = document.createElement("FORM");
	document.body.appendChild(submitForm);
	submitForm.method = "POST";
	submitForm.action = url;
	submitForm.name = "login";
	return submitForm;
}

function getNewHidden(id, value){
	var hidden = document.createElement("input");
	hidden.type = "hidden";
	hidden.id = id;
	hidden.name = id;
	hidden.value = value;
	return hidden;
}


