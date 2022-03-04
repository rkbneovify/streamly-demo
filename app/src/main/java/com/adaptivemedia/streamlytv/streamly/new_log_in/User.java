
package com.adaptivemedia.streamlytv.streamly.new_log_in;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class User {

    @SerializedName("id")
    @Expose
    private Long id;
    @SerializedName("provider_name")
    @Expose
    private Object providerName;
    @SerializedName("provider_id")
    @Expose
    private Object providerId;
    @SerializedName("name")
    @Expose
    private String name;
    @SerializedName("email")
    @Expose
    private String email;
    @SerializedName("contact")
    @Expose
    private String contact;
    @SerializedName("gender")
    @Expose
    private Object gender;
    @SerializedName("dob")
    @Expose
    private String dob;
    @SerializedName("zipcode")
    @Expose
    private String zipcode;
    @SerializedName("address")
    @Expose
    private String address;
    @SerializedName("country")
    @Expose
    private String country;
    @SerializedName("google_id")
    @Expose
    private Object googleId;
    @SerializedName("last_login")
    @Expose
    private Object lastLogin;
    @SerializedName("email_verified_at")
    @Expose
    private String emailVerifiedAt;
    @SerializedName("is_active")
    @Expose
    private Integer isActive;
    @SerializedName("created_at")
    @Expose
    private String createdAt;
    @SerializedName("updated_at")
    @Expose
    private String updatedAt;
    @SerializedName("stripe_id")
    @Expose
    private String stripeId;
    @SerializedName("card_brand")
    @Expose
    private String cardBrand;
    @SerializedName("card_last_four")
    @Expose
    private String cardLastFour;
    @SerializedName("trial_ends_at")
    @Expose
    private Object trialEndsAt;
    @SerializedName("avatar")
    @Expose
    private Object avatar;

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Object getProviderName() {
        return providerName;
    }

    public void setProviderName(Object providerName) {
        this.providerName = providerName;
    }

    public Object getProviderId() {
        return providerId;
    }

    public void setProviderId(Object providerId) {
        this.providerId = providerId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getContact() {
        return contact;
    }

    public void setContact(String contact) {
        this.contact = contact;
    }

    public Object getGender() {
        return gender;
    }

    public void setGender(Object gender) {
        this.gender = gender;
    }

    public String getDob() {
        return dob;
    }

    public void setDob(String dob) {
        this.dob = dob;
    }

    public String getZipcode() {
        return zipcode;
    }

    public void setZipcode(String zipcode) {
        this.zipcode = zipcode;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getCountry() {
        return country;
    }

    public void setCountry(String country) {
        this.country = country;
    }

    public Object getGoogleId() {
        return googleId;
    }

    public void setGoogleId(Object googleId) {
        this.googleId = googleId;
    }

    public Object getLastLogin() {
        return lastLogin;
    }

    public void setLastLogin(Object lastLogin) {
        this.lastLogin = lastLogin;
    }

    public String getEmailVerifiedAt() {
        return emailVerifiedAt;
    }

    public void setEmailVerifiedAt(String emailVerifiedAt) {
        this.emailVerifiedAt = emailVerifiedAt;
    }

    public Integer getIsActive() {
        return isActive;
    }

    public void setIsActive(Integer isActive) {
        this.isActive = isActive;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }

    public String getUpdatedAt() {
        return updatedAt;
    }

    public void setUpdatedAt(String updatedAt) {
        this.updatedAt = updatedAt;
    }

    public String getStripeId() {
        return stripeId;
    }

    public void setStripeId(String stripeId) {
        this.stripeId = stripeId;
    }

    public String getCardBrand() {
        return cardBrand;
    }

    public void setCardBrand(String cardBrand) {
        this.cardBrand = cardBrand;
    }

    public String getCardLastFour() {
        return cardLastFour;
    }

    public void setCardLastFour(String cardLastFour) {
        this.cardLastFour = cardLastFour;
    }

    public Object getTrialEndsAt() {
        return trialEndsAt;
    }

    public void setTrialEndsAt(Object trialEndsAt) {
        this.trialEndsAt = trialEndsAt;
    }

    public Object getAvatar() {
        return avatar;
    }

    public void setAvatar(Object avatar) {
        this.avatar = avatar;
    }

}
