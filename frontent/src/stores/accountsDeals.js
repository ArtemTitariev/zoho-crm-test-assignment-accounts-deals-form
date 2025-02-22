import { defineStore } from "pinia";

export const useAccountsDealsStore = defineStore("accountsDealsStore", {
  state: () => {
    return {
      message: "",
      errors: {},
      isSuccess: null,
      isLoading: false,
    };
  },
  actions: {
    async createDealAndAccount(formData) {
      this.errors = {};
      this.message = "";
      this.isSuccess = false;
      this.isLoading = true;

      try {
        const res = await fetch("/api/zoho/create-deal-and-account", {
          method: "post",
          body: JSON.stringify(formData),
        });

        const data = await res.json();

        if (res.ok) {
          this.message = data.message || "Success";
          this.errors = {};
          this.isSuccess = true;
        } else {
          this.message = data.message || "Something went wrong";
          this.errors = data.errors || {};
        }
      } catch (error) {
        console.error("Network error:", error);
        this.message = "Network error occurred";
      } finally {
        this.isLoading = false;
      }
    },
  },
});
