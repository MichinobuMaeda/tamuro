import axios from 'axios'

export const releaseUiNewVersion = ({ state }) => state.db.collection('service').doc('status').update({
  version: state.conf.version
})

const onServiceStatusChanged = async ({ commit, state }, { doc, i18n }) => {
  commit('setService', doc)
  i18n.locale = (state.service && state.service.status && state.service.status.locale) || i18n.locale
  if (state.service.status.version > state.conf.version) {
    const headers = {
      'Pragma': 'no-cache',
      'Expires': '-1',
      'Cache-Control': 'no-cache'
    }
    await axios.get(window.location.href, headers)
    await axios.get(window.location.href.replace(/#.*/, '').replace(/\/*/, '') + '/service-worker.js', headers)
    window.location.reload(true)
  }
}

export const getServiceStatus = async ({ commit, state }, { i18n }) => {
  await onServiceStatusChanged({ commit, state }, { doc: await state.db.collection('service').doc('status').get(), i18n })
  state.db.collection('service').doc('status').onSnapshot(async doc => {
    await onServiceStatusChanged({ commit, state }, { doc, i18n })
  })
}
