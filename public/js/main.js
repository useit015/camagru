const customUpload = document.querySelector(".custom-file-upload");
const imgInput = document.querySelector(".imgInput");
const video = document.querySelector('.player');
const snap = document.querySelector('.snap');
const canvas = document.querySelector('.photo');
const capture = document.querySelector('.capture');
const type = document.querySelector('.capture-mode');
const typeInput = document.querySelector('#type');
const uploadBtnType = document.querySelector('.form-group.upload-grp');
const captureBtnType = document.querySelector('.form-group.capture-grp');
const captureBtn = document.querySelector('.capture-btn');
const radios = document.querySelectorAll('.form-check-input.sup');
const canvasSup = document.querySelector('.canvas_sup');
const overlay = document.querySelector('.overlay');
const likesClose = document.querySelector('.likes-close_btn');
const likesOpen = document.querySelector('.likes-counter');
const likesBox = document.querySelector('.likes-box');
const changePassword = document.querySelector('.settings-change_password');
const notifCheckbox = document.querySelector('#notif');
const notifLabel = document.querySelector('.form-check-label.notif');
let typeCapture = true;
let captureFlag = true;
let pictureIsAllowed = false;
let intervalId = 0;

Number.prototype.map = function (in_min, in_max, out_min, out_max) {
	return (this - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}

function getVideo(video) {
	navigator.mediaDevices.getUserMedia({
		video: true,
		audio: false
	}).then(stream => {
		video.srcObject = stream;
		video.play();
	}).catch(err => console.error(`Oups !!`, err));
}

function drawInCanvas() {
	const ctx = canvas.getContext('2d');
	const width = video.videoWidth;
	const height = video.videoHeight;
	canvas.width = width;
	canvas.height = height;
	intervalId = setInterval(() => {
		ctx.drawImage(video, 0, 0, width, height);
		let pixels = ctx.getImageData(0, 0, width, height);
		ctx.putImageData(pixels, 0, 0);
	}, 16);
}

function allowPic(flag) {
	if (flag)
		if (document.querySelector('input[name=super]:checked') != null)
			return false;
	return true;
}

function stopRender() {
	clearInterval(intervalId);
	intervalId = 0;
}

function init() {
	if (video && canvas) {
		getVideo(video);
		video.addEventListener('canplay', drawInCanvas);
	}
}

if (customUpload)
	customUpload.addEventListener('click', () => document.querySelector(".imgInput").click());

if (imgInput) {
	imgInput.addEventListener('change', e => {
		const fileName = e.target.value.split("\\");
		const name = fileName[fileName.length - 1];
		customUpload.innerHTML = name.length <= 20 ? name : name.substring(0, 20) + '...';
		if (canvas) {
			let img = new Image();
			img.addEventListener('load', e => {
				const ctx = canvas.getContext('2d');
				clearInterval(intervalId);
				intervalId = 0;
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
				pictureIsAllowed = true;
				captureBtn.disabled = allowPic(pictureIsAllowed);
			});
			img.src = URL.createObjectURL(e.target.files[0]);
		}
	});
}

if (capture)
	capture.addEventListener('click', () => {
		if (captureFlag) {
			snap.currentTime = 0;
			snap.play();
			document.querySelector('.imgInputData').value = canvas.toDataURL('image/jpeg');
			capture.textContent = 'Try another one ?';
			stopRender();
			pictureIsAllowed = true;
			captureBtn.disabled = allowPic(pictureIsAllowed);
		} else {
			stopRender();
			drawInCanvas();
			pictureIsAllowed = false;
			captureBtn.disabled = allowPic(pictureIsAllowed);
			capture.textContent = 'Capture';
		}
		captureFlag = !captureFlag;
	});

if (type) {
	type.addEventListener('click', () => {
		if (typeCapture) {
			uploadBtnType.style.display = 'block';
			captureBtnType.style.display = 'none';
			type.innerHTML = 'Switch to camera';
			stopRender();
			captureFlag = true;
			canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
			typeInput.value = 'retro';
		} else {
			uploadBtnType.style.display = 'none';
			captureBtnType.style.display = 'block';
			stopRender();
			drawInCanvas();
			capture.textContent = 'Capture';
			imgInput.value = '';
			customUpload.innerHTML = 'Upload Image';
			type.innerHTML = 'Switch to retro';
			typeInput.value = 'camera';
		}
		typeCapture = !typeCapture;
		pictureIsAllowed = false;
		captureBtn.disabled = allowPic(pictureIsAllowed);
	});
}

if (radios) {
	radios.forEach(cur => {
		cur.addEventListener('change', () => {
			canvasSup.src = document.querySelector('input[name=super]:checked').value;
			canvasSup.style.display = 'auto';
			document.querySelector('#x').value = 400;
			document.querySelector('#y').value = 300;
			captureBtn.disabled = allowPic(pictureIsAllowed);
		});
	});
}

if (canvas) {
	canvas.addEventListener('click', e => {
		console.log(e.target);
		document.querySelector('#x').value = e.offsetX.map(0, e.target.offsetWidth, 0, 800);
		document.querySelector('#y').value = e.offsetY.map(0, e.target.offsetHeight, 0, 600);
		canvasSup.style.left = `${e.offsetX}px`;
		canvasSup.style.top = `${e.offsetY}px`;
	});
}

if (overlay)
	overlay.addEventListener('click', e => {if (e.target == overlay) overlay.style.display = 'none';});

if (likesClose)
	likesClose.addEventListener('click', () => overlay.style.display = 'none');

if (likesOpen)
	likesOpen.addEventListener('click', () => overlay.style.display = 'block');

if (changePassword)
	changePassword.addEventListener('click', () => overlay.style.display = 'block');

if (notifCheckbox)
	notifCheckbox.addEventListener('change', () => notifCheckbox.value = notifCheckbox.value == 'check' ? 'uncheck' : 'check');

init();