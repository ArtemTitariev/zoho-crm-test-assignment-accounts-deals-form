<script setup>
import { reactive } from "vue";

const formData = reactive({
  account_name: "",
  account_website: "",
  account_phone: "",
  deal_name: "",
  deal_stage: "",
});
</script>

<template>
  <main>
    <h1 class="title">Create account and deal</h1>

    <form @submit.prevent="console.log(formData)" class="w-1/2 mx-auto">
      <div class="form-group">
        <div class="form-control">
          <label>Account name</label>
          <input
            type="text"
            placeholder="Enter account name"
            v-model="formData.account_name"
          />
        </div>
        <div class="form-control">
          <label>Account website</label>
          <input
            type="text"
            placeholder="Enter website"
            v-model="formData.account_website"
          />
        </div>

        <div class="form-control">
          <label>Account phone</label>
          <input
            type="tel"
            placeholder="Enter phone"
            v-model="formData.account_phone"
          />
        </div>
      </div>
      <div class="form-group">
        <div class="form-control">
          <label>Deal name</label>
          <input
            type="text"
            placeholder="Enter deal name"
            v-model="formData.deal_name"
          />
        </div>
        <div class="form-control">
          <label>Deal stage</label>
          <input
            type="text"
            placeholder="Enter deal stage"
            v-model="formData.deal_stage"
          />
        </div>
      </div>

      <button class="primary-btn" type="primary">Submit</button>
    </form>
  </main>
</template>
