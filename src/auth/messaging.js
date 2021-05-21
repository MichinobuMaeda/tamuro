import {
  storeClientId,
  restoreClientId
} from './localStrage'

export const initializeMessaging = async (
  { standalone, webPushCertificateKey, messaging, db, state }
) => {
  if (state.me.id && messaging && standalone && webPushCertificateKey && process.env.NODE_ENV === 'production') {
    const token = await messaging.getToken({ vapidKey: webPushCertificateKey })
    messaging.onMessage(payload => {
      console.log('Message received. ', payload)
    })
    if (token) {
      const clientId = restoreClientId() || storeClientId(state.me.id)
      const ts = new Date()
      await db.runTransaction(async transaction => {
        const accountRef = db.collection('accounts').doc(state.me.id)
        const account = await transaction.get(accountRef)
        await transaction.update(accountRef, {
          messagingTokens: [
            ...(account.data().messagingTokens || []).filter(item => item.clientId !== clientId),
            { token, ts, clientId }
          ],
          updatedAt: ts
        })
      })
    }
  }
}
