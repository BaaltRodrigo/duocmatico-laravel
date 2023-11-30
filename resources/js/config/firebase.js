// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";
import { getAnalytics } from "firebase/analytics";
import { getAuth } from "firebase/auth";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: process.env.MIX_FIRESTORE_API_KEY,
  authDomain: process.env.MIX_FIRESTORE_AUTH_DOMAIN,
  projectId: process.env.MIX_FIRESTORE_PROJECT_ID,
  storageBucket: process.env.MIX_FIRESTORE_STORAGE_BUCKET,
  messagingSenderId: process.env.MIX_FIRESTORE_MESSAGING_SENDER,
  appId: process.env.MIX_FIRESTORE_APP_ID,
  measurementId:process.env.MIX_FIRESTORE_MEASUREMENT_ID,
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Firebase utilities
const db = getFirestore(app);
const analytics = getAnalytics(app);
const auth = getAuth(app);

export { analytics, app, auth, db };
