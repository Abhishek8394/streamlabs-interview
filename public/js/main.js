function fetchData(token){
	var url = $('#commentApiEndpoint').attr('data-url');
	var yturl = $('#videoUrl').attr('data-url');
	var csrfToken = $('#csrfToken').attr('data-value');
	var params = {'urlbar':yturl, '_token':csrfToken};
	if(token!=null){
		params['pageToken'] = token;
	}
	console.log(params);
	$.post(url,params).done(function(data){
		rearrangeData(data);
	}).fail(function(e){
		console.log(e);
	});
}
function formatUserData(userName, comment){
	var result = document.createElement('div');
	result.className = 'panel';
	var userNameBlock = document.createElement('b');
	userNameBlock.innerHTML = userName;
	var commentBlock = document.createElement('p');
	commentBlock.innerHTML = comment;
	result.appendChild(userNameBlock);
	result.appendChild(document.createElement('br'));
	result.appendChild(commentBlock);
	// result = "<div class=\"panel\">" + 
	// 	"<b>" + userName + "</b><br>" + 
	// 	"<p>" + 
	// 		comment
	// 	"</p>" + 
	// "</div>";
	return result;
}
function rearrangeData(data){
	var comments = [];
	var commentContainer = document.getElementById('commentContainer');	
	var i=0;
	for(i = 0; i < data['modelData']['items'].length; i++){
		var item = data['modelData']['items'][i];
		var userName = item['snippet']['topLevelComment']['snippet']['authorDisplayName'];
		var comment = item['snippet']['topLevelComment']['snippet']['textDisplay'];
		commentContainer.appendChild(formatUserData(userName,comment));
		// console.log(item['snippet']['topLevelComment']['snippet']['textDisplay']);
	}
};

$(document).ready(function(){
	$('.tokenPagers').click(function(e){
		var token = $(this).attr('data-token');
		// console.log(token);
		fetchData(token);
	});
});
