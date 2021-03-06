const hasValidKey = async (db, req) => {
  const apiKey = req.query.key
  if (!apiKey) {
    return false
  }
  const conf = await db.collection('service').doc('conf').get()
  return conf.data().apiKey === apiKey
}

const apiKeyValidator = ({ db }) => async (req, res, next) => {
  if (!(await hasValidKey(db, req))) {
    res.status(401)
    return res.send('401 Unauthorized')
  }
  return next()
}

module.exports = {
  apiKeyValidator
}
