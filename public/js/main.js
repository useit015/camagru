const profileImg = document.querySelector(".profile-img");
const customUpload = document.querySelector(".custom-file-upload");
const imgInput = document.querySelector(".imgInput");
const video = document.querySelector('.player');
const canvas = document.querySelector('.photo');
const capture = document.querySelector('.capture');
const type = document.querySelector('#type');
const uploadBtnType = document.querySelector('.form-group.upload-grp');
const captureBtnType = document.querySelector('.form-group.capture-grp');
const radios = document.querySelectorAll('.form-check-input');
const canvasSup = document.querySelector('.canvas_sup');
let typeCapture = true;
let captureFlag = true;
let i;

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
	i = setInterval(() => {
		ctx.drawImage(video, 0, 0, width, height);
		let pixels = ctx.getImageData(0, 0, width, height);
		ctx.putImageData(pixels, 0, 0);
	}, 16);
}

function init() {
	if (video && canvas) {
		getVideo(video);
		video.addEventListener('canplay', drawInCanvas);
	}
}

if (profileImg)
	profileImg.addEventListener('click', e => document.querySelector('.profile-settings').classList.toggle('active'));

if (customUpload)
	customUpload.addEventListener('click', e => document.querySelector(".imgInput").click());
if (imgInput) {
	imgInput.addEventListener('change', e => {
		const fileName = e.target.value.split("\\");
		const name = fileName[fileName.length - 1];
		customUpload.innerHTML = name.length <= 20 ? name : name.substring(0, 20) + '...';
		if (canvas) {
			let img = new Image();
			img.onload = e => {
				clearInterval(i);
				canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
			}
			img.src = URL.createObjectURL(e.target.files[0]);
		}
	});
}

if (capture)
	capture.addEventListener('click', e => {
		if (captureFlag) {
			document.querySelector('.imgInputData').value = canvas.toDataURL('image/jpeg');
			capture.textContent = 'Try another one ?';
			clearInterval(i);
		} else {
			drawInCanvas();
			capture.textContent = 'Capture';
		}
		captureFlag = !captureFlag;
	});

if (type) {
	type.addEventListener('change', e => {
		if (typeCapture) {
			uploadBtnType.style.display = 'block';
			captureBtnType.style.display = 'none';
		} else {
			uploadBtnType.style.display = 'none';
			captureBtnType.style.display = 'block';
			drawInCanvas();
			capture.textContent = 'Capture';
			imgInput.value = '';
			customUpload.innerHTML = 'Upload Image';
		}
		typeCapture = !typeCapture;
	});
}

if (radios) {
	radios.forEach(cur => {
		cur.addEventListener('change', e => {
			canvasSup.src = document.querySelector('input[name=super]:checked').value;
			canvasSup.style.display = 'auto';
		});
	});
}

if (canvas) {
	canvas.addEventListener('click', e => {
		document.querySelector('#x').value = e.offsetX.map(0, e.target.offsetWidth, 0, 800);
		document.querySelector('#y').value = e.offsetY.map(0, e.target.offsetHeight, 0, 600);
		canvasSup.style.left = `${e.offsetX}px`;
		canvasSup.style.top = `${e.offsetY}px`;
	});
}

init();