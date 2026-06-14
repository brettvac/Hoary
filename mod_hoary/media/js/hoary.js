class HoaryThemeSwitcher {
  constructor() {
    this.iconDark = "🌙";
    this.iconLight = "☀️";
    this.cookieName = "jHoaryMode";
    this.dmsBtns = null;

/* 
 * Bind the init() method to the current object instance.
 * This ensures that "this" inside init() always refers
 * to the class instance, regardless of how the method is called.
 */
this.init = this.init.bind(this);

/* 
 * Bind the onReady() method to the current object instance.
 * This prevents the execution context from changing when
 * the method is used as an event or callback handler.
 */
this.onReady = this.onReady.bind(this);

/* 
 * Bind the onButtonClick() method to the current object instance.
 * This ensures that properties and methods accessed through "this"
 * remain available when the click event is triggered.
 */
this.onButtonClick = this.onButtonClick.bind(this);

/* 
 * Bind the bindButtonEvents() method to the current object instance.
 * This guarantees that the correct object context is preserved
 * whenever the method is invoked.
 */
this.bindButtonEvents = this.bindButtonEvents.bind(this);

/* 
 * Bind the updateButtonCallback() method to the current object instance.
 * This ensures that "this" continues to reference the class instance
 * when the callback is executed by external code or event handlers.
 */
this.updateButtonCallback = this.updateButtonCallback.bind(this);
    
    this.init();
  }

  init() {
    if (typeof window.jHoaryInitialized !== "undefined") return;
    window.jHoaryInitialized = true;

    document.addEventListener("DOMContentLoaded", this.onReady);
  }

  onReady() {
    this.dmsBtns = document.querySelectorAll("button.hoary-button");
    if (!this.dmsBtns.length) return;

    // Fetch the global module variables
    const options = Joomla.getOptions('mod_hoary.vars', { iconStyle: 0 });
    
    // Set class icons globally based on the fetched vars
    if (parseInt(options.iconStyle, 10) === 1) {
      this.iconDark = "<i class='fas fa-moon'></i>";
      this.iconLight = "<i class='fas fa-sun'></i>";
    }

    let savedMode = this.getCookie(this.cookieName);
    let isDark = savedMode === "true"; 
    
    this.setCookie(this.cookieName, isDark, 365);
    this.updateMode(isDark);

    this.dmsBtns.forEach(this.bindButtonEvents);
  }

  bindButtonEvents(btn) {
    this.updateButton(btn, this.getCookie(this.cookieName) === "true");
    btn.addEventListener("click", this.onButtonClick);
  }

  onButtonClick() {
    let currentMode = this.getCookie(this.cookieName) === "true";
    let newMode = !currentMode;
    
    this.setCookie(this.cookieName, newMode, 365);
    this.updateMode(newMode);
    
    this.dmsBtns.forEach(this.updateButtonCallback);
  }

  updateButtonCallback(btn) {
    this.updateButton(btn, this.getCookie(this.cookieName) === "true");
  }

  updateButton(btn, isDark) {
    const icon = btn.querySelector(".header-item-icon > span");
    const text = btn.querySelector(".header-item-text");
    
    // Removed inline JS styling; CSS handles all visual changes now.
    if (isDark) {
      if (icon) {
        icon.innerHTML = this.iconDark;
      }
      if (text) {
        try { text.innerHTML = Joomla.JText._("MOD_HOARY_DARK"); } catch (e) {}
      }
    } else {
      if (icon) {
        icon.innerHTML = this.iconLight;
      }
      if (text) {
        try { text.innerHTML = Joomla.JText._("MOD_HOARY_LIGHT"); } catch (e) {}
      }
    }
  } 

  updateMode(isDark) {
    document.documentElement.setAttribute('data-hoary-theme', isDark ? 'dark' : 'light');
  }

  setCookie(name, value, days) {
    let expires = "";
    if (days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/; SameSite=Lax";
  }

  getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i].trim();
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }
}

// Instantiate the class immediately to replace the IIFE pattern
const hoarySwitcherInstance = new HoaryThemeSwitcher();