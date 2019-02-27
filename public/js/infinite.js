let page = 0;
let pollingForData = false;
const url = 'http://localhost/';
const xhr = new XMLHttpRequest();
const contentContainer = document.querySelector('.gallery');
const loadingContainer = document.querySelector('.loading-container');

const getDistFromBottom = () => Math.max(document.body.offsetHeight - (window.pageYOffset + window.innerHeight), 0);

const template = post => {
	return `
		<a href="${url}/camagru/posts/show/${post.postId}">
			<img class="gallery-image" src="${url}/${post.postImg}">
			<div class="gallery-item-details">
				<div class="gallery-img_container d-flex justify-content-center align-items-start">
					<div class="cmnt-img" style="background-image:url(${url}/${post.userImg})"></div>
				</div>
				<h5>${post.userName}<span>${post.postCreated}</span></h5>
			</div>
			<h5></h5>
			<div class="gallery-item-details_like">
				<i class="fa fa-thumbs-up gallery-item-details_like-icon px-2"></i>
				<span>${post.postLikeCount}</span>
			</div>
			<div class="gallery-item-details_cmnt">
				<i class="fa fa-comment gallery-item-details_cmnt-icon px-2"></i>
				<span>${post.postCmntCount}</span>
			</div>
		</a>
	`;
}

const getNextPage = () => {
	let distToBottom = getDistFromBottom();
	if (!pollingForData && distToBottom >= 0 && distToBottom <= 500) {
		pollingForData = true;
		setTimeout(() => {
			xhr.open('GET', `${url}/camagru/posts/pages/${++page}`, true);
			xhr.send();
		}, 300);
	}
}

xhr.addEventListener('load', () => {
	if (xhr.status === 200) {
		pollingForData = false;
		let dataObj = JSON.parse(xhr.responseText);
		if (!dataObj.length) return document.removeEventListener('scroll', getNextPage);
		dataObj.forEach(post => {
			const item = document.createElement('div')
			item.classList.add('gallery-item');
			item.innerHTML = template(post);
			contentContainer.appendChild(item);
		});
	}
});

document.addEventListener('scroll', getNextPage);