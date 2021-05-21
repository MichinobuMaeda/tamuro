<template>
  <v-row>
    <v-col class="col-12">
      <v-row>
        <v-col class="title--text col-4 text-right">{{ $t('Site name') }}</v-col>
        <v-col class="col-8">
          <TextEditor
            :label="$t('Site name')"
            v-model="name"
            :rules="[ruleRequired]"
            :editable="me.priv.manager"
            :disabled="!!state.waitProc"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col class="title--text col-4 text-right">URL</v-col>
        <v-col class="col-8">
          <TextEditor
            label="URL"
            v-model="hosting"
            :rules="[ruleRequired, ruleUrl]"
            :editable="me.priv.admin"
            :disabled="!!state.waitProc"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col class="title--text col-4 text-right">{{ $t('Max count of addresses') }}</v-col>
        <v-col class="col-8">
          <TextEditor
            type="number"
            :label="$t('Max count of addresses')"
            v-model="profileAddressCount"
            :rules="[rulePositive]"
            :editable="me.priv.manager"
            :disabled="!!state.waitProc"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col class="title--text col-4 text-right">API key</v-col>
        <v-col class="col-8">
          <TextEditor
            label="API key"
            v-model="apiKey"
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
      name: computed({
        get: () => state.service.conf.name,
        set: str => waitFor(() => update(state.service.conf, { name: str }))
      }),
      hosting: computed({
        get: () => state.service.conf.hosting,
        set: str => waitFor(() => update(state.service.conf, { hosting: str }))
      }),
      profileAddressCount: computed({
        get: () => state.service.conf.profileAddressCount,
        set: str => waitFor(() => update(state.service.conf, { profileAddressCount: Number(str) }))
      }),
      apiKey: computed({
        get: () => state.service.conf.apiKey,
        set: str => waitFor(() => update(state.service.conf, { apiKey: str }))
      })
    }
  }
}
</script>
