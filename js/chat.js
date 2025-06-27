// Config Firebase
const firebaseConfig = {
  apiKey: "AIzaSyC8tux0A1SnExsctAqmtbiGdP4LRSd_jfM",
  authDomain: "chat-a807e.firebaseapp.com",
  projectId: "chat-a807e",
  storageBucket: "chat-a807e.appspot.com",
  messagingSenderId: "771369457585",
  appId: "1:771369457585:web:43be81bcba1909c66b8f90",
  measurementId: "G-3K72M2W1BB"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();
const messagesRef = db.collection("chat");

// Mostra messaggi
messagesRef.orderBy("timestamp").onSnapshot(snapshot => {
  const messages = document.getElementById("messages");
  messages.innerHTML = "";
  snapshot.forEach(doc => {
    const msg = doc.data();
    messages.innerHTML += `<div class="message"><strong>${msg.name}:</strong> ${msg.text}</div>`;
  });
});

// Invia messaggio
function sendMessage() {
  const name = document.getElementById("username").value.trim();
  const text = document.getElementById("messageInput").value.trim();
  if (name && text) {
    messagesRef.add({
      name,
      text,
      timestamp: firebase.firestore.FieldValue.serverTimestamp()
    });
    document.getElementById("messageInput").value = "";
  }
}