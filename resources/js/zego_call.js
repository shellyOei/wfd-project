import {ZegoExpressEngine} from 'zego-express-engine-webrtc'

let zg;
let roomIDInput = document.getElementById('roomID');
let toggleMicButton = document.getElementById('toggleMic');
// Ensure these buttons exist in your HTML
let toggleSpeakerButton = document.getElementById('toggleSpeaker');
let localAudio = document.getElementById('local-audio');
let remoteStreamsDiv = document.getElementById('remote-streams');

let currentUserID = '';
let currentUserName = '';
let currentAppID = 0;
let currentToken = '';
let currentRoomID = ''; // Renamed to avoid clash with nextCall's 'roomID' local variable
let localStream = null;
let remoteStreams = new Map();

// Call duration variables
let callDurationInterval = null;
let callStartTime = 0;
let callDurationDisplay = document.getElementById('call-duration'); // New element



// --- ZegoCloud Functions ---
window.initZegoClient = initZegoClient;

async function initZegoClient($frontendUuid) {
    const fetched = await fetchZegoToken($frontendUuid);
    if (!fetched) return false; 

    if (zg) {
        zg.destroy(); // Destroy existing instance if any
    }

    zg = new ZegoExpressEngine(currentAppID, currentToken);
    window.zg = zg; // Make it globally accessible for debugging

    // --- ZegoCloud Event Handlers ---
    zg.on('roomStateUpdate', (roomID, state, errorCode, extendedData) => {
        console.log('roomStateUpdate: ', roomID, state, errorCode, extendedData);
        if (state === 'CONNECTED') {
            console.log('Connected to room:', roomID);
            toggleMicButton.disabled = false;
            toggleSpeakerButton.disabled = false; // Make sure this is enabled if speaker control is available
            publishStream();
            startCallDuration(); // Start tracking duration
        } else if (state === 'DISCONNECTED') {
            console.log('Disconnected from room:', roomID);
            toggleMicButton.disabled = true;
            toggleSpeakerButton.disabled = true;
            stopPublishingAndPlaying();
            remoteStreams.clear();
            remoteStreamsDiv.innerHTML = '';
            stopCallDuration(); // Stop tracking duration
        }
    });

    zg.on('publisherStateUpdate', (result) => {
        console.log('publisherStateUpdate: ', result);
    });

    zg.on('playerStateUpdate', (result) => {
        console.log('playerStateUpdate: ', result);
    });

    zg.on('roomStreamUpdate', async (roomID, updateType, streamList) => {
        console.log('roomStreamUpdate: ', roomID, updateType, streamList);
        if (updateType === 'ADD') {
            for (let i = 0; i < streamList.length; i++) {
                const stream = streamList[i];
                if (stream.mediaStream.getAudioTracks().length > 0) {
                    console.log('Playing remote stream:', stream.streamID);
                    const remoteStream = await zg.startPlayingStream(stream.streamID, {
                        audio: true,
                        video: false
                    });
                    const audioEl = document.createElement('audio');
                    audioEl.autoplay = true;
                    audioEl.controls = false;
                    audioEl.srcObject = remoteStream;
                    audioEl.id = `remote-audio-${stream.streamID}`;
                    remoteStreamsDiv.appendChild(audioEl);
                    remoteStreams.set(stream.streamID, audioEl);
                }
            }
        } else if (updateType === 'DELETE') {
            for (let i = 0; i < streamList.length; i++) {
                const stream = streamList[i];
                console.log('Stopping remote stream:', stream.streamID);
                if (remoteStreams.has(stream.streamID)) {
                    const audioEl = remoteStreams.get(stream.streamID);
                    audioEl.srcObject = null;
                    audioEl.remove();
                    remoteStreams.delete(stream.streamID);
                }
                zg.stopPlayingStream(stream.streamID);
            }
        }
    });
    // --- End ZegoCloud Event Handlers ---
    return true; // Return true if client initialized successfully
}

// Function to join a ZegoCloud room
async function joinZegoRoom(roomIDToJoin) {
    if (!roomIDToJoin) {
        console.error('No Room ID provided to joinZegoRoom.');
        alert('Cannot join call: No Room ID provided.');
        return;
    }

    currentRoomID = roomIDToJoin;
    roomIDInput.value = currentRoomID; // Update the input field

    if (!zg) {
        const initialized = await initZegoClient();
        if (!initialized) {
            console.error('ZegoClient failed to initialize.');
            return;
        }
    }

    try {
        await zg.loginRoom(currentRoomID, currentToken, { userID: currentUserID, userName: currentUserName });
        console.log('Logged into room:', currentRoomID);
    } catch (error) {
        console.error('Login room failed:', error);
        alert('Failed to join room: ' + error.message);
    }
}

async function publishStream() {
    try {
        localStream = await zg.createStream({
            camera: { audio: true, video: false },
            microphone: { audio: true }
        });
        localAudio.srcObject = localStream;
        localAudio.play();

        

        await zg.startPublishingStream(currentUserID + '_stream', localStream);
        console.log('Local stream published:', currentUserID + '_stream');

        toggleMicButton.innerText = localStream.getAudioTracks()[0]?.enabled ? 'Mute Mic' : 'Unmute Mic';

    } catch (error) {
        console.error('Failed to publish stream:', error);
        alert('Failed to get local audio: ' + error.message + '. Please ensure microphone access is granted.');
    }
}

async function leaveZegoRoom() {
    if (zg && currentRoomID) {
        await zg.logoutRoom(currentRoomID);
        console.log('Logged out of room:', currentRoomID);
        stopPublishingAndPlaying();
    }
}

function stopPublishingAndPlaying() {
    if (localStream) {
        zg.stopPublishingStream(currentUserID + '_stream');
        zg.destroyStream(localStream);
        localStream = null;
        localAudio.srcObject = null;
    }
    remoteStreams.forEach((audioEl, streamID) => {
        audioEl.srcObject = null;
        audioEl.remove();
        zg.stopPlayingStream(streamID);
    });
    remoteStreams.clear();
}

// --- Call Duration Logic ---
function startCallDuration() {
    callStartTime = Date.now();
    if (callDurationInterval) {
        clearInterval(callDurationInterval);
    }
    callDurationInterval = setInterval(() => {
        const elapsedSeconds = Math.floor((Date.now() - callStartTime) / 1000);
        const minutes = Math.floor(elapsedSeconds / 60);
        const seconds = elapsedSeconds % 60;
        callDurationDisplay.innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }, 1000);
}

function stopCallDuration() {
    if (callDurationInterval) {
        clearInterval(callDurationInterval);
        callDurationInterval = null;
    }
    callDurationDisplay.innerText = '00:00'; // Reset display
}


// --- Event Listeners for UI Buttons ---
document.addEventListener('DOMContentLoaded', () => {
    // These listeners are now defined here, not directly in Blade
    toggleMicButton.addEventListener('click', () => {
        if (localStream) {
            const audioTrack = localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                toggleMicButton.innerText = audioTrack.enabled ? 'Mute Mic' : 'Unmute Mic';
            }
        }
    });

    toggleSpeakerButton.addEventListener('click', () => {
        remoteStreams.forEach(audioEl => {
            audioEl.muted = !audioEl.muted;
        });
        alert('Toggling mute for all remote streams. Check browser/system speaker settings for overall control.');
    });

    // We'll call joinZegoRoom/leaveZegoRoom from nextCall() or specific buttons if needed
    // The "joinButton" and "leaveButton" are commented out in the Blade, so their listeners are not needed here.
});


// Expose joinZegoRoom and leaveZegoRoom to the global scope if nextCall() or other global functions need them
// Or, better yet, refactor `nextCall` to live in this file if possible.
// For now, let's assume `nextCall` remains in the Blade script block.
window.joinZegoRoom = joinZegoRoom;
window.leaveZegoRoom = leaveZegoRoom;
