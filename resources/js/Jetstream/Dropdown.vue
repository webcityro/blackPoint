<template>
  <li class="nav-item dropdown"
      v-click-away="() => show = false">
    <a :id="id" class="nav-link" role="button" @click="show = true">
      <slot name="trigger"></slot>
    </a>

    <div
      :class="['dropdown-menu', 'dropdown-menu-right', 'animate', 'slideIn', { show }]"
      :aria-labelledby="id"
    >
      <slot name="content"></slot>
    </div>
  </li>
</template>

<script>
  export default {
    props: {
      id: {
        type: String,
        required: true
      },
    },

    data () {
      return {
        show: false
      };
    }
  }
</script>
