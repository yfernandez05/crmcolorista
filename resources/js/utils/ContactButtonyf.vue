<template>
  <div class="input-group-append">

    <button
      v-if="showWhatsapp && isValidPhone"
      class="btn btn-success d-flex align-items-center mr-2"
      @click="openWhatsApp"
    >
      <i class="fab fa-whatsapp mr-1"></i>
      <span class="d-none d-sm-inline">WhatsApp</span>
    </button>

    <button
      v-if="showEmail && isValidEmail"
      class="btn btn-primary d-flex align-items-center"
      @click="openEmail"
    >
      <i class="fas fa-envelope mr-1"></i>
      <span class="d-none d-sm-inline">Correo</span>
    </button>

  </div>
</template>

<script>
export default {
  props: {
    phone: {
      type: String,
      required: false,
      default: ""
    },
    email: {
      type: String,
      required: false,
      default: ""
    },
    showWhatsapp: {
      type: Boolean,
      default: false
    },
    showEmail: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    isValidPhone() {
      return this.phone && /^\d{9}$/.test(this.phone) && this.phone.startsWith('9');
    },
    isValidEmail() {
      return this.email && /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(this.email);
    }
  },
  methods: {
    openWhatsApp() {
      const url = `https://web.whatsapp.com/send?phone=51${this.phone}`;
      window.open(url, "_blank");
    },
    openEmail() {
      const mailto = `mailto:${this.email}`;
      window.open(mailto, "_blank");
    }
  }
};
</script>

<style scoped>
.contact-buttons {
  display: flex;
  gap: 5px;
}
</style>
