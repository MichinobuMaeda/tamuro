import Firebase from 'firebase/app'
import 'firebase/firestore'
import 'firebase/functions'
import 'firebase/storage'
import conf from '../conf'

const firebase = Firebase.initializeApp(conf.firebase)
const db = firebase.firestore()
const functions = firebase.functions()
// if (location.hostname === 'localhost') {
//   db.settings({
//     host: 'localhost:8080',
//     ssl: false
//   })
//   functions.useFunctionsEmulator('http://localhost:5000')
// }
const storage = firebase.storage()
const message = window.localStorage.getItem('message') ? JSON.parse(window.localStorage.getItem('message')) : { key: 'noMessage', params: {} }

export default {
  conf,
  layoutSize: {},
  reqPage: null,
  firebase,
  db,
  functions,
  storage,
  loading: [ 'start' ],
  message,
  me: null,
  groups: [],
  chatRooms: {},
  chatImages: {},
  messages: {},
  accounts: [],
  users: [],
  profiles: {},
  service: {},
  unsub: {},
  preferences: {},
  limitedPriv: false
}
