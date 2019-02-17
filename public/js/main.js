const customUpload = document.querySelector(".custom-file-upload");
const imgInput = document.querySelector(".imgInput");
const video = document.querySelector('.player');
const snap = document.querySelector('.snap');
const canvas = document.querySelector('.photo');
const capture = document.querySelector('.capture');
const type = document.querySelector('.capture-mode');
const typeInput = document.querySelector('#type');
const uploadIcon = document.querySelector('.upload-icon');
const uploadBtnType = document.querySelector('.form-group.upload-grp');
const captureBtnType = document.querySelector('.form-group.capture-grp');
const captureBtn = document.querySelector('.capture-btn');
const resetFilter = document.querySelector('.reset-filters');
const filterContainer = document.querySelector('.filters_container');
const radios = document.querySelectorAll('.form-check-input.sup');
const canvasSup = document.querySelector('.canvas_sup');
const overlay = document.querySelector('.overlay');
const likesClose = document.querySelector('.likes-close_btn');
const likesOpen = document.querySelector('.likes-counter');
const likesBox = document.querySelector('.likes-box');
const changePassword = document.querySelector('.settings-change_password');
const notifCheck = document.querySelector('#notif');
const notifLabel = document.querySelector('.form-check-label.notif');
const likeCounter = document.querySelector('.likes-counter');
const flashDelBtn = document.querySelector('.flash-del');
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
	ctx.scale(-1, 1);
	const width = video.videoWidth;
	const height = video.videoHeight;
	canvas.width = width;
	canvas.height = height;
	ctx.scale(-1, 1);
	intervalId = setInterval(() => {
		ctx.drawImage(video, -width, 0, width, height);
		let pixels = ctx.getImageData(0, 0, width, height);
		ctx.putImageData(jsEffect(pixels), 0, 0);
	}, 16);
}

function jsEffect(pixels) {
	if (document.querySelector('#splitCheck').checked)
		pixels = splitEffect(pixels);
	if (document.querySelector('#invertCheck').checked)
		pixels = invertEffect(pixels);
	return brightnessEffect(colorEffect(pixels));
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

function invertEffect(pixels) {
	for (let i = 0; i < pixels.data.length; i += 4) {
		pixels.data[i] ^= 250;
		pixels.data[i + 1] ^= 250;
		pixels.data[i + 2] ^= 250;
	}
	return pixels;
}

function colorEffect(pixels) {
	const red = document.querySelector('#redSlider').value * 255 / 100;
	const green = document.querySelector('#greenSlider').value * 255 / 100;
	const blue = document.querySelector('#blueSlider').value * 255 / 100;
	for (let i = 0; i < pixels.data.length; i += 4) {
		pixels.data[i] += red;
		pixels.data[i + 1] += green;
		pixels.data[i + 2] += blue;
	}
	return pixels;
}

function splitEffect(pixels) {
	for (let i = 0; i < pixels.data.length; i += 4) {
		pixels.data[i - 140] = pixels.data[i];
		pixels.data[i + 110] = pixels.data[i + 1];
		pixels.data[i - 170] = pixels.data[i + 2];
	}
	return pixels;
}

function brightnessEffect(pixels) {
	let brightness = document.querySelector('#briSlider').value * 255 / 100;
	for (var i = 0; i < pixels.data.length; i += 4) {
		pixels.data[i] += brightness;
		pixels.data[i + 1] += brightness;
		pixels.data[i + 2] += brightness;
	}
	return pixels;
}

if (video && canvas) {
	getVideo(video);
	video.addEventListener('canplay', drawInCanvas);
}

if (customUpload)
	customUpload.addEventListener('click', () => document.querySelector(".imgInput").click());

if (imgInput) {
	imgInput.addEventListener('change', e => {
		const fileName = e.target.value.split("\\");
		const name = fileName[fileName.length - 1];
		if (!['jpg', 'jpeg', 'png'].includes(name.split('.')[name.split('.').length - 1])) {
			e.target.value = '';
			return;
		}
		customUpload.innerHTML = name.length <= 20 ? name : name.substring(0, 20) + '...';
		if (canvas) {
			let img = new Image();
			img.addEventListener('load', e => {
				const ctx = canvas.getContext('2d');
				clearInterval(intervalId);
				intervalId = 0;
				ctx.clearRect(-canvas.width, 0, canvas.width, canvas.height);
				const r = img.width / img.height;
				if (canvas.width / canvas.height > r)
					canvas.width = canvas.height * r;
				else
					canvas.height = canvas.width / r;	
				ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
				pictureIsAllowed = true;
				captureBtn.disabled = allowPic(pictureIsAllowed);
				uploadIcon.style.display = 'none';
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
			uploadIcon.style.display = 'block';
			uploadBtnType.style.display = 'block';
			captureBtnType.style.display = 'none';
			resetFilter.style.display = 'none';
			filterContainer.style.display = 'none';
			type.innerHTML = 'Switch to camera';
			stopRender();
			captureFlag = true;
			canvas.getContext('2d').clearRect(-canvas.width, 0, canvas.width, canvas.height);
			typeInput.value = 'retro';
		} else {
			uploadBtnType.style.display = 'none';
			captureBtnType.style.display = 'block';
			uploadIcon.style.display = 'none';
			resetFilter.style.display = 'block';
			filterContainer.style.display = 'block';
			stopRender();
			drawInCanvas();
			capture.textContent = 'Capture';
			imgInput.value = '';
			customUpload.innerHTML = 'Upload Image';
			type.innerHTML = 'Switch to retro';
			typeInput.value = 'camera';
			document.querySelectorAll('.form-control-range').forEach(cur => cur.disabled = 0);
			document.querySelectorAll('.form-check-input').forEach(cur => cur.disabled = 0);
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
		let r = e.target.offsetWidth / e.target.offsetHeight;
		document.querySelector('#x').value = e.offsetX.map(0, e.target.offsetWidth, 0, 800);
		document.querySelector('#y').value = e.offsetY.map(0, e.target.offsetHeight, 0, 800 / r);
		canvasSup.style.left = `${e.offsetX}px`;
		canvasSup.style.top = `${e.offsetY}px`;
	});
}

if (resetFilter) {
	resetFilter.addEventListener('click', () => {
		document.querySelectorAll('.form-control-range').forEach(cur => cur.value = 0);
		document.querySelectorAll('.form-check-input').forEach(cur => cur.checked = 0);
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

if (notifCheck)
	notifCheck.addEventListener('change', () => notifCheck.value = notifCheck.value == 'check' ? 'uncheck' : 'check');

if (likeCounter)
	likeCounter.style.display = likeCounter.textContent == '0 Like' ? 'none' : 'block';

if (flashDelBtn)
	flashDelBtn.addEventListener('click', () => document.querySelector('.alert').style.display = 'none');