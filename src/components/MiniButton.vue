<template>
  <span>
    <v-icon
      v-if="!disabled"
      color="primary"
      @click="$emit('click')"
    >
      {{ icon }}
    </v-icon>
    <v-icon
      v-else
      color="grey"
    >
      {{ icon }}
    </v-icon>
  </span>
</template>

<script>
export default {
  name: 'MiniButton',
  props: {
    icon: String,
    disabled: {
      type: Boolean,
      default: false
    }
  }
}
</script>
