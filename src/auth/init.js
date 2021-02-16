import { initMe } from '../store'
import { onSignInWithEmailLink } from './email'
import {
  restoreRequestedRoute,
  eraseRequestedRoute
} from './localStrage'

export const getAuthState = async ({ db, auth, state }) => {
  if (auth.isSignInWithEmailLink(window.location.href)) {
    await onSignInWithEmailLink(auth)
  } else {
    auth.onAuthStateChanged(async user => {
      if (user) {
        try {
          await initMe({ db, auth, state }, user.uid)
        } catch (e) {
          state.me = {}
          state.loading = false
        }
      } else {
        state.me = {}
        state.loading = false
      }
    })
  }
}

export const returnLastRoute = router => {
  const route = restoreRequestedRoute()
  eraseRequestedRoute()
  route && route.name && router.push(route).catch(() => {})
}

export const signOut = async ({ auth }) => {
  await auth.signOut()
}
