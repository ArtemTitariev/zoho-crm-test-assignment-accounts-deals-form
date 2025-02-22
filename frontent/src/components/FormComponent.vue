<script setup>
import { useAccountsDealsStore } from "@/stores/accountsDeals";
import { storeToRefs } from "pinia";
import { reactive } from "vue";

const store = useAccountsDealsStore();

const { errors, message, isLoading, isSuccess } = storeToRefs(store);
const { createDealAndAccount } = store;

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

    <form
      @submit.prevent="createDealAndAccount(formData)"
      class="w-1/2 mx-auto"
    >
      <p
        v-if="message"
        class="message-block"
        :class="isSuccess ? 'text-green-600' : 'text-red-600'"
      >
        {{ message }}
      </p>

      <div class="form-group">
        <div class="form-control">
          <label>Account name</label>
          <input
            type="text"
            placeholder="Enter account name"
            v-model="formData.account_name"
          />
          <p v-if="errors?.account_name" class="error">
            {{ errors.account_name[0] }}
          </p>
        </div>
        <div class="form-control">
          <label>Account website</label>
          <input
            type="url"
            placeholder="Enter website"
            v-model="formData.account_website"
          />
          <p v-if="errors?.account_website" class="error">
            {{ errors.account_website[0] }}
          </p>
        </div>

        <div class="form-control">
          <label>Account phone</label>
          <input
            type="tel"
            placeholder="Enter phone"
            v-model="formData.account_phone"
          />
          <p v-if="errors?.account_phone" class="error">
            {{ errors.account_phone[0] }}
          </p>
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
          <p v-if="errors?.deal_name" class="error">
            {{ errors.deal_name[0] }}
          </p>
        </div>
        <div class="form-control">
          <label>Deal stage</label>
          <input
            type="text"
            placeholder="Enter deal stage"
            v-model="formData.deal_stage"
          />
          <p v-if="errors?.deal_stage" class="error">
            {{ errors.deal_stage[0] }}
          </p>
        </div>
      </div>

      <button class="primary-btn" type="primary" :disabled="isLoading">
        Submit
      </button>
    </form>
  </main>
</template>
