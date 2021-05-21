<template>
  <v-row>
    <v-col class="col-12">
      <v-row>
        <v-col class="title--text col-4 text-right">{{ $t('Notification expiration') }}</v-col>
        <v-col class="col-4">
          <TextEditor
            type="number"
            :label="$t('Notification expiration')"
            v-model="notificationExpirationTime"
            :rules="[ruleNotNegative]"
            :editable="me.priv.admin || me.priv.manager"
            :disabled="!!state.waitProc"
          />
        </v-col>
        <v-col class="col-4">
          {{ msecToDaysAndTime(notificationExpirationTime || 0) }}
        </v-col>
      </v-row>
      <v-row>
        <v-col class="title--text col-4 text-right">{{ $t('Notification pause repetition') }}</v-col>
        <v-col class="col-4">
          <TextEditor
            type="number"
            :label="$t('Notification pause repetition')"
            v-model="notificationPauseRepetitionTime"
            :rules="[ruleNotNegative]"
            :editable="me.priv.admin || me.priv.manager"
            :disabled="!!state.waitProc"
          />
        </v-col>
        <v-col class="col-4">
          {{ msecToDaysAndTime(notificationPauseRepetitionTime || 0) }}
        </v-col>
      </v-row>
      <v-row>
        <v-col class="title--text col-4 text-right">{{ $t('Notification title') }}</v-col>
        <v-col class="col-8">
          <TextEditor
            :label="$t('Notification icon')"
            v-model="notificationTitle"
            :rules="[ruleRequired]"
            :editable="me.priv.admin || me.priv.manager"
            :disabled="!!state.waitProc"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col class="title--text col-4 text-right">{{ $t('Notification icon') }}</v-col>
        <v-col class="col-8">
          <TextEditor
            :label="$t('Notification icon')"
            v-model="notificationIconPath"
            :rules="[ruleRequired]"
            :editable="me.priv.admin"
            :disabled="!!state.waitProc"
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { computed } from '@vue/composition-api'
import { useStore } from '../../store'
import TextEditor from '../../components/TextEditor'

export default {
  name: 'AdminService',
  components: {
    TextEditor
  },
  setup () {
    const store = useStore()
    const { state, waitFor, update } = store

    return {
      ...store,
      notificationExpirationTime: computed({
        get: () => state.service.conf.notificationExpirationTime,
        set: str => waitFor(() => update(state.service.conf, { notificationExpirationTime: Number(str) }))
      }),
      notificationPauseRepetitionTime: computed({
        get: () => state.service.conf.notificationPauseRepetitionTime,
        set: str => waitFor(() => update(state.service.conf, { notificationPauseRepetitionTime: Number(str) }))
      }),
      notificationTitle: computed({
        get: () => state.service.conf.notificationTitle,
        set: str => waitFor(() => update(state.service.conf, { notificationTitle: str }))
      }),
      notificationIconPath: computed({
        get: () => state.service.conf.notificationIconPath,
        set: str => waitFor(() => update(state.service.conf, { notificationIconPath: str }))
      })
    }
  }
}
</script>
