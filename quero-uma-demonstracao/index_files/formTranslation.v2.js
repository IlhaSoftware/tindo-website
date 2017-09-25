/* global JotForm, $, $$, $A, $H, window, document, navigator, console, XMLHttpRequest, ActiveXObject, Ajax */
'use strict';

if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, '');
  };
}

var FormTranslation = {
  languages: {
    'English (US)': 'en',
    'English (UK)': 'en-UK',
    'Español': 'es',
    'Français': 'fr',
    'Italiano': 'it',
    'Português': 'pt',
    'Deutsch': 'de',
    'Türkçe': 'tr',
    'Català': 'ca',
    'Nederlands': 'nl',
    'Svenska': 'sv',
    'Magyar': 'hu',
    'Norsk': 'no',
    'Dansk': 'da',
    'Română': 'ro',
    'Finnish': 'fi',
    'Polski': 'pl',
    'Russian': 'ru',
    'Chinese': 'zh',
    'Afrikaans': 'af',
    'Albanian': 'sq',
    'Amharic': 'am',
    'Arabic‬‎': 'ar',
    'Armenian': 'hy',
    'Azerbaijani': 'az',
    'Euskara': 'eu',
    'Belarusian': 'be',
    'Bengali': 'bn',
    'Bihari': 'bh',
    'Bosnian': 'bs',
    'Breton': 'br',
    'Bulgarian': 'bg',
    'Chinese(Simplified Han)': 'zh-CN',
    'Chinese(Traditional Han)': 'zh-TW',
    'Corsican': 'co',
    'Croatian': 'hr',
    'Czech': 'cs',
    'English (Australia)': 'en-AU',
    'English (Canada)': 'en-CA',
    'English (New Zealand)': 'en-NZ',
    'English (South Africa)': 'en-ZA',
    'Esperanto': 'eo',
    'Estonian': 'et',
    'Faroese': 'fo',
    'Filipino': 'fil',
    'French (Canada)': 'fr-CA',
    'French (France)': 'fr-FR',
    'French (Switzerland)': 'fr-CH',
    'Galician': 'gl',
    'Georgian': 'ka',
    'German (Austria)': 'de-AT',
    'German (Germany)': 'de-DE',
    'German (Switzerland)': 'de-CH',
    'Greek': 'el',
    'Guarani': 'gn',
    'Gujarati': 'gu',
    'Haitian Creole': 'ht',
    'Hausa': 'ha',
    'Hawaiian': 'haw',
    'Hebrew': 'he',
    'Hindi': 'hi',
    'Icelandic': 'is',
    'Indonesian': 'id',
    'Interlingua': 'ia',
    'Irish': 'ga',
    'Italian (Switzerland)': 'it-CH',
    'Japanese': 'ja',
    'Javanese': 'jw',
    'Kannada': 'kn',
    'Kazakh': 'kk',
    'Khmer': 'km',
    'Kirghiz': 'ky',
    'Korean': 'ko',
    'Kurdish': 'ku',
    'Lao': 'lo',
    'Latin': 'la',
    'Latvian': 'lv',
    'Lingala': 'ln',
    'Lithuanian': 'lt',
    'Macedonian': 'mk',
    'Malay': 'ms',
    'Malayalam': 'ml',
    'Maltese': 'mt',
    'Marathi': 'mr',
    'Moldavian': 'mo',
    'Mongolian': 'mn',
    'Nepali': 'ne',
    'Norwegian Bokmål': 'nb',
    'Norwegian Nynorsk': 'nn',
    'Occitan': 'oc',
    'Oriya': 'or',
    'Oromo': 'om',
    'Pashto': 'ps',
    'Persian': 'fa',
    'Portuguese (Brazil)': 'pt-BR',
    'Portuguese (Portugal)': 'pt-PT',
    'Punjabi': 'pa',
    'Quechua': 'qu',
    'Romansh': 'rm',
    'Scottish Gaelic': 'gd',
    'Serbian': 'sr',
    'Serbo-Croatian': 'sh',
    'Shona': 'sn',
    'Sindhi': 'sd',
    'Sinhala': 'si',
    'Slovak': 'sk',
    'Slovenian': 'sl',
    'Somali': 'so',
    'Southern Sotho': 'st',
    'Spanish (Latin America)': 'es-419',
    'Sundanese': 'su',
    'Swahili': 'sw',
    'Tajik': 'tg',
    'Tamil': 'ta',
    'Tatar': 'tt',
    'Telugu': 'te',
    'Thai': 'th',
    'Tigrinya': 'ti',
    'Tonga': 'to',
    'Turkmen': 'tk',
    'Twi': 'tw',
    'Uighur': 'ug',
    'Ukrainian': 'uk',
    'Urdu': 'ur',
    'Uzbek': 'uz',
    'Vietnamese': 'vi',
    'Welsh (Cymraeg)': 'cy',
    'Western Frisian': 'fy',
    'Xhosa': 'xh',
    'Yiddish': 'yi',
    'Yoruba': 'yo',
    'Zulu': 'zu',
    'en': 'English (US)',
    'en-UK': 'English (UK)',
    'es': 'Español',
    'fr': 'Français',
    'it': 'Italiano',
    'pt': 'Português',
    'de': 'Deutsch',
    'tr': 'Türkçe',
    'ca': 'Català',
    'nl': 'Nederlands',
    'sv': 'Svenska',
    'hu': 'Magyar',
    'no': 'Norsk',
    'da': 'Dansk',
    'ro': 'Română',
    'fi': 'Finnish',
    'pl': 'Polski',
    'ru': 'Russian',
    'zh': 'Chinese',
    'af': 'Afrikaans',
    'sq': 'Albanian',
    'am': 'Amharic',
    'ar': 'Arabic‬‎',
    'hy': 'Armenian',
    'az': 'Azerbaijani',
    'eu': 'Euskara',
    'be': 'Belarusian',
    'bn': 'Bengali',
    'bh': 'Bihari',
    'bs': 'Bosnian',
    'br': 'Breton',
    'bg': 'Bulgarian',
    'zh-CN': 'Chinese (Simplified Han)',
    'zh-TW': 'Chinese (Traditional Han)',
    'co': 'Corsican',
    'hr': 'Croatian',
    'cs': 'Czech',
    'en-AU': 'English (Australia)',
    'en-CA': 'English (Canada)',
    'en-NZ': 'English (New Zealand)',
    'en-ZA': 'English (South Africa)',
    'eo': 'Esperanto',
    'et': 'Estonian',
    'fo': 'Faroese',
    'fil': 'Filipino',
    'fr-CA': 'French (Canada)',
    'fr-FR': 'French (France)',
    'fr-CH': 'French (Switzerland)',
    'gl': 'Galician',
    'ka': 'Georgian',
    'de-AT': 'German (Austria)',
    'de-DE': 'German (Germany)',
    'de-CH': 'German (Switzerland)',
    'el': 'Greek',
    'gn': 'Guarani',
    'gu': 'Gujarati',
    'ht': 'Haitian Creole',
    'ha': 'Hausa',
    'haw': 'Hawaiian',
    'he': 'Hebrew',
    'hi': 'Hindi',
    'is': 'Icelandic',
    'id': 'Indonesian',
    'ia': 'Interlingua',
    'ga': 'Irish',
    'it-CH': 'Italian (Switzerland)',
    'ja': 'Japanese',
    'jw': 'Javanese',
    'kn': 'Kannada',
    'kk': 'Kazakh',
    'km': 'Khmer',
    'ky': 'Kirghiz',
    'ko': 'Korean',
    'ku': 'Kurdish',
    'lo': 'Lao',
    'la': 'Latin',
    'lv': 'Latvian',
    'ln': 'Lingala',
    'lt': 'Lithuanian',
    'mk': 'Macedonian',
    'ms': 'Malay',
    'ml': 'Malayalam',
    'mt': 'Maltese',
    'mr': 'Marathi',
    'mo': 'Moldavian',
    'mn': 'Mongolian',
    'ne': 'Nepali',
    'nb': 'Norwegian Bokmål',
    'nn': 'Norwegian Nynorsk',
    'oc': 'Occitan',
    'or': 'Oriya',
    'om': 'Oromo',
    'ps': 'Pashto',
    'fa': 'Persian',
    'pt-BR': 'Portuguese (Brazil)',
    'pt-PT': 'Portuguese (Portugal)',
    'pa': 'Punjabi',
    'qu': 'Quechua',
    'rm': 'Romansh',
    'gd': 'Scottish Gaelic',
    'sr': 'Serbian',
    'sh': 'Serbo-Croatian',
    'sn': 'Shona',
    'sd': 'Sindhi',
    'si': 'Sinhala',
    'sk': 'Slovak',
    'sl': 'Slovenian',
    'so': 'Somali',
    'st': 'Southern Sotho',
    'es-419': 'Spanish (Latin America)',
    'su': 'Sundanese',
    'sw': 'Swahili',
    'tg': 'Tajik',
    'ta': 'Tamil',
    'tt': 'Tatar',
    'te': 'Telugu',
    'th': 'Thai',
    'ti': 'Tigrinya',
    'to': 'Tonga',
    'tk': 'Turkmen',
    'tw': 'Twi',
    'ug': 'Uighur',
    'uk': 'Ukrainian',
    'ur': 'Urdu',
    'uz': 'Uzbek',
    'vi': 'Vietnamese',
    'cy': 'Welsh (Cymraeg)',
    'fy': 'Western Frisian',
    'xh': 'Xhosa',
    'yi': 'Yiddish',
    'yo': 'Yoruba',
    'zu': 'Zulu'
  },

  rtlLanguages: ['ar', 'he'],
  urlParams: {
    'dropdown' : '', /* dropdown=hidden */
    'language' : '' /* lang code*/
  },
  originalFormElements: [],
  dictionary: {}, //{"id" : "text"}
  formElements: [],
  to: '',
  properties: {},
  browserLang: '',
  translations: {},
  getTranslations: function(languages, callback) {
    var self = this;

    var formID = (function() {
      return document.querySelector('.jotform-form').getAttribute('id');
    })();

    if (!languages) {
      languages = [this.properties.primaryLanguage];
    }

    new Ajax.Jsonp(JotForm.server, {
      force: true,
      parameters: {
        action: 'getFormTranslations',
        formID: formID,
        langCode: languages.join(',')
      },
      method: 'GET',
      onComplete: function(res) {
        var translations = null;
        if (res.responseJSON) {
          var resJSON = res.responseJSON;

          if (resJSON.success) {
            translations = resJSON.message.translations;
            Object.extend(self.translations, translations);
            self.setDictionary();
            self.setBrowserLanguage(resJSON.message.acceptLanguage, languages);
            callback();
          } else {
            console.log('onError');
          }
        }
      }
    });

  },
  setBrowserLanguage: function(browserLanguages, formLanguages) {

    try {

      if (!this.properties.detectUserLanguage) return;

      var languageOptions = [];
      var langs = browserLanguages.split(",");
      for(var i=0; i<langs.length; i++) {
        var lang = langs[i].split(";");
        if(lang.length == 1) {
          languageOptions.push({lang: lang[0], weight: 1.0});
        } else {
          languageOptions.push({lang: lang[0], weight: lang[1].replace("q=", "")});
        }
      }

      languageOptions.sort(function(a, b){return b.weight-a.weight});

      var match = false;
      var partialMatch = false;

      for(i = 0; i < languageOptions.length;  i++) {

        var browserLanguage = languageOptions[i].lang;
        if(browserLanguage == "en-US") browserLanguage = "en";

        for(var j=0; j<formLanguages.length; j++) {

          var formLanguage = formLanguages[j];
          if(formLanguage == browserLanguage) {
            match = formLanguage;
            break;
          }

          if(!partialMatch) {
            if(formLanguage.split("-")[0] == browserLanguage.split("-")[0]) {
              partialMatch = formLanguage;
            }
          }

        }

        if(match) break;
      }

      this.browserLang = match ? match : partialMatch;

    } catch(e) {
      console.log(e);
    }

  },
  setDictionary: function() {
    var self = this;
    var translations = this.translations,
        primaryLanguage = this.properties.primaryLanguage,
        _originalLanguage = {},
        dictionary = {};

    if (typeof translations[primaryLanguage] === 'undefined') {
      this.dictionary = dictionary;
      return false;
    }

    /* checks form fields are actually same with primary language
     * if not it search for primary translations
     * if can not find primary translations, it calls getTranslations and breaks
     */
    var getAllTranslations = false;
    var originalLanguage = (function() {
      var form = $$('.jotform-form')[0];
      var formID = '#' + form.getAttribute('id');
      var inputs = $$(formID + ' .form-line');
      var searchText = null;
      var _originalLanguage = null;
      var langMatches = {};
      var translationValues = (function() {
        var values = {};
        $H(translations).each(function(item) {
          values[item.key] = Object.values(item.value);
        });
        return values;
      })();

      inputs.each(function(input) {
        // don't check complicated fields
        if (['control_textarea', 'control_input'].indexOf(input.getAttribute('data-type')) >= 0) { return; }
        var inputID = '#' + input.getAttribute('id');
        var labels = $$(inputID + ' label');
        if (labels.length <= 0) { return; }
        var label = labels[0];
        var textContent = typeof label.textContent != 'undefined' ? label.textContent : label.innerText;
        textContent = textContent.replace('*', '');
        textContent = textContent.trim();
        if (textContent.length <= 0) { return; }

        $H(translationValues).each(function(translation) {
          if (translation.value.indexOf(textContent) >= 0) {
            if(!(translation.key in langMatches)) {
              langMatches[translation.key] = 1;
            } else {
              langMatches[translation.key]++;
            }
          }
        });
      });

      for(var langMatch in langMatches) {
        if(_originalLanguage == null || langMatches[langMatch] > langMatches[_originalLanguage.key]) {
          _originalLanguage = {
            key: langMatch,
            translation: translations[langMatch]
          };
        }

      }
      if(_originalLanguage == null) {
        _originalLanguage = {
          key: primaryLanguage,
          translation: translations[primaryLanguage]
        }
      }

      return _originalLanguage;
    })();

    $H(originalLanguage.translation).each(function(item) {
      _originalLanguage[item.value] = item.value;
    });
    dictionary[originalLanguage.key] = _originalLanguage;

    $H(translations).each(function(language) {
      var langKey = language.key;
      if (langKey !== originalLanguage.key) {
        dictionary[langKey] = {};
        var langTranslation = language.value;
        $H(langTranslation).each(function(lang) {
          var key = translations[originalLanguage.key][lang.key];
          dictionary[langKey][key] = lang.value;
        });
      }
    });

    this.dictionary = dictionary;

  },
  init: function (properties) {
    var self = this;

    this.properties = properties;

    var languages = (function() {
      var options = self.properties.options.split('|');
      var languages = [];
      $A(options).each(function(item) {
        languages.push(self.languages[item]);
      });
      return languages;
    })();

    this.getTranslations(languages, function() {
      self.getUrlParams();
      self.trimMultiSpaces();
      self.getFormElements();
      self.setOriginalFormElements();
      self.clickEventForDropDown();
      self.choosePrimaryLanguage();
      self.setShowStatus();
      self.setTheme();

      if(self.properties.saveUserLanguage && document.readCookie("savedUserLanguage")) {
          self.translateToOriginal();
          self.to = document.readCookie("savedUserLanguage");
          self.setFlagForDropDown();
          self.actionTranslation();
          JotForm.runAllCalculations();
      } else if (self.properties.detectUserLanguage) { // Open form in user's browser language
        if (self.properties.options.indexOf(self.languages[self.browserLang]) >= 0) {
          self.translateToOriginal();
          self.to = self.browserLang;
          self.setFlagForDropDown();
          self.actionTranslation();
          JotForm.runAllCalculations();
        }
      }
    });

    // Close language dropdown on out-of-scope clicks
    $(document).on('click', function(event) {
      if (event.target != $$('.dd-placeholder')[0]) {
        if (!$('langList').hasClassName('dn')){
          $('langList').addClassName('dn');
        }
      }
    });
  },
  getFormElements: function(){
    var self = this;
    var labels = $$('label:not(.form-product-container)');
    var sublabels = $$('.form-sub-label');
    var spans = $$('span[id="coupon-header"], span[id="subtotal-text"], span[id="tax-text"], span[id="shipping-text"], span[id="total-text"], span.form-item-subtotal, span[id^="collapse-text"], span[class="span_print"], span[class="form-product-name"], span[class="form-product-child-label"]');
    
    var inputs = $$('input');
    var textarea = $$('textarea');
    var buttons = $$('button:not(.form-submit-print)');
    var h1 = $$('h1');
    var h2 = $$('h2');
    var h3 = $$('h3');
    var subHeader = $$('div[id^="subHeader"]');
    var selects = $$('select:not([dropdown-type="control_language"]):not(.time-dropdown)'); /* dropdowns except control_language */
    var ths = $$('th');
    var hover = $$('div.form-description-content');
    var multipleUploadButton = $$('.qq-upload-button');

    ths = ths.filter(function(e){
      if (e.children.length > 0) {
        if (typeof e.children[0].id != 'undefined' && e.children[0].id && e.children[0].id == 'coupon-header') {

        } else if(e.up('.form-product-child-table')) {
            return;
        }else {
          return e;
        }
      } else {
        return e;
      }
    });

    var freeText = $$('div[class="form-html"][id^="text_"]');
    self.formElements = self.formElements.concat(labels, sublabels, inputs, h1, h2, h3,
                        subHeader, textarea, buttons, selects, ths, freeText,
                        spans, hover, multipleUploadButton);
  },
  translate: function(word){
    if (typeof word !== "string") { return; }
    var self = this;
    var tmpWord = word.replace(/^\s+|\s+$/g, '').strip();

    if (tmpWord.length <= 0) { return ''; }

    if (typeof self.dictionary[self.to] != 'undefined' && typeof self.dictionary[self.to][tmpWord] != 'undefined' && self.dictionary[self.to][tmpWord]) {
      return self.dictionary[self.to][tmpWord];
    } else {
      return word;
    }
  },
  setText: function(e){
    var self = this;
    var type = typeof e.textContent != 'undefined' ? 'textContent' : typeof e.innerText != 'undefined' ? 'innerText' : '';
    if (e.tagName == 'DIV' && e.getAttribute('class') == 'form-description-content') type = 'innerHTML';
    if (type) {
      if (e.getAttribute('class') == 'form-html' && e.getAttribute('id').match(/^text/)) {
        if (e.id != self.translate(e.id)) {
          e.innerHTML = self.preventXSS(self.translate(e.id));
        }
      } else {
        e[type] = self.translate(e[type]);
        if(e.hasClassName("form-submit-button")) {
          e.oldText = e[type];
        }
      }
    }
  },
  setOriginalFormElements: function(){
    var self = this;
    var tmpElement = {};
    var matrixRank = -1;
    $A(self.formElements).each(function(e){
      tmpElement = {};

      if (e.id) {
        tmpElement.id = e.id;
        if (e.tagName == 'INPUT') {
          tmpElement.type = 'placeholder';
          tmpElement.placeholder = e.placeholder;
        } else if (e.tagName == 'TEXTAREA' && e.getAttribute('data-customhint')) {
          tmpElement.type = 'placeholder';
          tmpElement.customHint = e.getAttribute('data-customhint');
        } else {
          tmpElement.type = 'control';
          tmpElement.html = e.innerHTML;
        }
        self.originalFormElements.push(tmpElement);
      } else {
        // create an ID for undefined labels

        if (e.tagName == 'DIV' && e.getAttribute('class') == 'form-description-content') {
          tmpElement.id = e.up('li.form-line').id;
          tmpElement.type = 'hover';
          tmpElement.html = e.innerHTML;
          self.originalFormElements.push(tmpElement);
        } else if (e.tagName == 'LABEL') {
          var siblings = $(e).siblings();

          if (siblings.length > 0 && siblings[0].getAttribute('id')) {

            $(e).setAttribute('id', 'label' + '_' + siblings[0].getAttribute('id'));

            tmpElement.id   = e.id;
            tmpElement.type = 'control';
            tmpElement.html = e.innerHTML;

            self.originalFormElements.push(tmpElement);
          } else if (typeof e.up('table.form-scale-table') != 'undefined' && isNaN(e.innerHTML)) {
            tmpElement.id   = '';
            tmpElement['for'] = e.getAttribute('for');
            tmpElement.type = 'scale';
            tmpElement.html = e.innerHTML;

            self.originalFormElements.push(tmpElement);
          }
        } else if (e.tagName == 'TH') {
          if (e.getAttribute('class') && e.getAttribute('class').indexOf('form-matrix') > -1) {
            if (e.getAttribute('class').indexOf('form-matrix-column-headers') > -1) {
              /* If there is more than 1 matrix on the form */
              if (e.getAttribute('class').indexOf('form-matrix-column_0') > -1) {
                matrixRank++;
              }
              tmpElement.rank = matrixRank;
            } else if (e.getAttribute('class').indexOf('form-matrix-row-headers') > -1) {
              tmpElement.rank = $$('.form-matrix-row-headers').indexOf(e);
            }

            tmpElement.id = '';
            tmpElement['class'] = e.getAttribute('class');
            tmpElement.type = 'matrixTH';
            tmpElement.html = e.innerHTML;

            self.originalFormElements.push(tmpElement);

          } else if (e.children.length === 0 && e.up('.form-address-table')) {
            tmpElement.rank = $$('.form-address-table th').indexOf(e);
            tmpElement.id = '';
            tmpElement.type = 'paymentAddressTH';
            tmpElement.html = e.innerHTML;

            self.originalFormElements.push(tmpElement);
          }
        } else if(e.hasClassName("qq-upload-button")) {
          if(e && e.up(".form-input, .form-input-wide")) {
            tmpElement.html = e.innerHTML;
            tmpElement.type = "multipleButton";
            tmpElement.id = e.up(".form-input, .form-input-wide").id;
            self.originalFormElements.push(tmpElement);
          }
        } else if(e.up('.form-product-child-table') && e.tagName == 'SPAN') {
            tmpElement.rank = $$('.form-product-child-table span').indexOf(e);
            tmpElement.id = '';
            tmpElement.type = 'subProductLabel';
            tmpElement.html = e.innerHTML;
            self.originalFormElements.push(tmpElement);
        }
      }
    });

    var formTexts = JotForm.texts;
    $H(formTexts).each(function (text) {

      if (typeof text.value !== typeof Function) {
        tmpElement = {};

        tmpElement.id = text.key;
        tmpElement.type = 'formText';
        tmpElement.html = text.value;

        self.originalFormElements.push(tmpElement);
      }

    });

  },
  translateToOriginal: function(){
    var self = this;
    self.originalFormElements.each(function(e){

      if (e.id && e.id != 'fill-form') {
        if (e.type == 'control') {
          $$('#' + e.id).each(function(item){
            item.update(e.html);
          });
        } else if (e.type == 'placeholder' && $(e.id) !== null) {
          if (typeof e.placeholder !== 'undefined') {
            $(e.id).hint(e.placeholder);
          } else if (typeof e.customHint !== 'undefined' && e.customHint !== null) {
            JotForm.setCustomHint(e.id, e.customHint);
          }
        } else if (e.type == 'formText') {
          var text = {};
          text[e.id] = e.html;
          JotForm.alterTexts(text);
        } else if (e.type == 'hover') {
          $$('li#' + e.id + ' div.form-description-content')[0].innerHTML = e.html;
        } else if(e.type == "multipleButton") {
          var button = $(e.id).down(".qq-upload-button");
          if(button) {
            button.update(e.html);
          }
        }
      } else if (e.type == 'matrixTH') {
        if (e['class'] && e['class'].indexOf('form-matrix') > -1) {
          if ($$('.' + e['class'].replace(' ', '.') + ":nth("+e['rank']+")").length > 0) {
            $$('.' + e['class'].replace(' ', '.') + ":nth("+e['rank']+")").first().innerHTML = e.html;
          }
        }
      } else if (e.type == 'paymentAddressTH') {
        $$('.form-address-table th')[e.rank].innerHTML = e.html;
      } else if (e.type == 'scale'){
        $$('label[for="' + e['for'] + '"]').last().innerHTML = e.html;
      } else if(e.type === 'subProductLabel') {
        $$('.form-product-child-table span')[e.rank].innerHTML = e.html;
      }
    });
  },
  actionTranslation: function(){
    var self = this;
    $A(self.formElements).each(function(e){
      if (e.getAttribute('class') == 'form-html' && e.getAttribute('id').match(/^text/)) {
        self.setText(e);
      } else if (e.tagName == 'INPUT') {
        if (typeof e.placeholder !== 'undefined' && e.placeholder.length > 0) {
          e.hint(self.translate(e.placeholder));
        }
      } else if (e.tagName == 'TEXTAREA') {
        var customHint = e.getAttribute('data-customhint');
        if (customHint) {
          var translation = self.translate(customHint);
          $(e.getAttribute('id')).value = '';
          JotForm.setCustomHint(e.getAttribute('id'), translation);
        }
      } else if (e.tagName == 'SELECT') {
        $A(e.options).each(function(o){
          //o.textContent = self.translate(o.textContent);
          self.setText(o);
        });
      } else if (e.tagName == 'DIV' && e.getAttribute('class') == 'form-description-content') {
        self.setText(e);
      } else {
        if(e.children.length > 0){
          var tmpElement = e.clone(true);
          $A(tmpElement.children).each(function(e){
            e.remove();
          });
          //tmpElement.textContent = self.translate(tmpElement.textContent);
          self.setText(tmpElement);

          if (e.children[0].hasClassName('form-product-details') || e.children[0].hasClassName('form-required')) {
          // for product price, insert after text or if label has asterisk (required) element
              tmpElement.insert({bottom: e.children[0]});
          } else {
              tmpElement.insert({top : e.children[0]});
          }
          e.innerHTML = tmpElement.innerHTML;
        } else {            
          self.setText(e);
        }
      }
    });

    var formTexts = JotForm.texts;
    $H(formTexts).each(function (item) {

      var text = {};

      text[item.key] = self.translate(item.value);

      JotForm.alterTexts(text);

    });

    this.translateDateTime();
  },
  translateDateTime: function() {
    if(!JotForm.calendarMonths) return;

    var months = JotForm.calendarMonths;
    JotForm.calendarMonthsTranslated = [];
    for(var i=0; i<months.length; i++) {
        JotForm.calendarMonthsTranslated.push(this.translate(months[i]));
    }

    var days = JotForm.calendarDays;
    JotForm.calendarDaysTranslated = [];
    for(var i=0; i<days.length; i++) {
        JotForm.calendarDaysTranslated.push(this.translate(days[i]));
    }

    JotForm.calendarTodayTranslated = this.translate(JotForm.calendarOther.today);
  },
  setFlagForDropDown: function(){
    var self = this;
    var dropdown = $$('.dd-placeholder')[0];

    dropdown.setAttribute('class', '');
    dropdown.addClassName('dd-placeholder ' + self.to);
    if($$('.dd-placeholder')[0].innerHTML){
      dropdown.update(self.languages[self.to]);
    }
    self.updateInput();
  },
  updateInput: function() {
    if($('input_language')) {
      $('input_language').setValue(this.languages[this.to]);
    }
    if($('input_language').triggerEvent) {
      $('input_language').triggerEvent('keyup');
    }
  },
  clickEventForDropDown: function(){
    var self = this;
    $('langDd').on('click', function(){
      $('langList').toggleClassName('dn');
    });
    $('langList').on('click', function(li){
      $('langList').toggleClassName('dn');


      var selectedIndices = {};
      $$('.form-line').each(function(field) {
        field.select("select").each(function(select) {
          selectedIndices[select.id] = select.selectedIndex;
        });
      });

      self.translateToOriginal();
      self.to = li.target.getAttribute('data-lang');

      self.setFlagForDropDown();

      var form = $$('.form-all')[0];
      if (self.rtlLanguages.indexOf(self.to) > -1) {
        form.setAttribute('dir', 'rtl');
      } else {
        form.setAttribute('dir', 'ltr');
      }

      if (typeof self.dictionary[self.to] === 'undefined') {
        self.getTranslations([self.to], function() {
          self.actionTranslation();
        });
      } else {
        self.actionTranslation();
      }


      if(self.properties.saveUserLanguage) {
        document.createCookie("savedUserLanguage", self.to);
      }

      for(var id in selectedIndices) {
        $(id).selectedIndex = selectedIndices[id];
      }

      JotForm.runAllCalculations();

    });
  },
  choosePrimaryLanguage: function(){
    var self = this;
    self.to = self.urlParams.language && self.languages[self.urlParams.language] ? self.urlParams.language : self.properties.primaryLanguage;
    self.setFlagForDropDown();
    self.actionTranslation();
  },
  setShowStatus: function(){
    var self = this;
    if (typeof self.properties.showStatus != 'undefined') {
      if (self.properties.showStatus == 'flag-with-nation') {

      } else if (self.properties.showStatus == 'nation-only') {
        /*
        $('langDd').insert('<style id="langDdCss">.lang-list li:before {background:none;}.dd-placeholder:before {background:none;}.lang-list li {text-align:center;padding-left:0;}.dd-placeholder{padding-left:16px;}</style>')
        */
        //$('langList').addClassName("only-nation");
        $$('.cont')[0].addClassName('only-nation');
      } else if (self.properties.showStatus == 'flag-only') {
        $$('#langList li').invoke('update', '');
        $$('#langDd div')[0].update('');
        $$('div[class="language-dd"][id="langDd"]')[0].setStyle({ 'width': '50px' });
        $$('ul[id="langList"] li').each(function (li) {
          li.addClassName('only-flag');
        });
      }
    }
    if($$('#langList li').length < 2){
      $('langDd').hide();
    }
  },
  setTheme: function(){
    var self = this;

    if (typeof self.properties.theme != 'undefined') {

      if (self.properties.theme == 'light-theme') {
        $('langList').addClassName('light');
      } else if (self.properties.theme == 'dark-theme'){
        $('langList').addClassName('dark');
      }
    } else {
      $('langList').addClassName('dark');
    }

  },
  trimMultiSpaces: function(){
    var dictionary = this.dictionary;
    var newKey;
    $H(this.dictionary).each(function(lang){
      $H(lang.value).each(function(e){
        newKey = e.key.replace(/\s+/gim, ' ').strip();
        if (newKey != e.key) {
          dictionary[lang.key][newKey] = e.value;
          delete dictionary[lang.key][e.key];
        }
      });
    });

  },
  preventXSS: function(html){

    var events = 'javascript|FSCommand|onAbort|onActivate|onAfterPrint|onAfterUpdate|onBeforeActivate|onBeforeCopy|onBeforeCut|onBeforeDeactivate|onBeforeEditFocus|onBeforePaste|onBeforePrint|onBeforeUnload|onBeforeUpdate|' +
    'onBegin|onBlur|onBounce|onCellChange|onChange|onClick|onContextMenu|onControlSelect|onCopy|onCut|onDataAvailable|onDataSetChanged|onDataSetComplete|onDblClick|onDeactivate|onDrag|onDragEnd|onDragLeave|onDragEnter|' +
    'onDragOver|onDragDrop|onDragStart|onDrop|onEnd|onError|onErrorUpdate|onFilterChange|onFinish|onFocus|onFocusIn|onFocusOut|onHashChange|onHelp|onInput|onKeyDown|onKeyPress|onKeyUp|onLayoutComplete|onLoad|onLoseCapture|' +
    'onMediaComplete|onMediaError|onMessage|onMouseDown|onMouseEnter|onMouseLeave|onMouseMove|onMouseOut|onMouseOver|onMouseUp|onMouseWheel|onMove|onMoveEnd|onMoveStart|onOffline|onOnline|onOutOfSync|onPaste|onPause|onPopState|' +
    'onProgress|onPropertyChange|onReadyStateChange|onRedo|onRepeat|onReset|onResize|onResizeEnd|onResizeStart|onResume|onReverse|onRowsEnter|onRowExit|onRowDelete|onRowInserted|onScroll|onSeek|onSelect|onSelectionChange|' +
    'onSelectStart|onStart|onStop|onStorage|onSyncRestored|onSubmit|onTimeError|onTrackChange|onUndo|onUnload|onURLFlip|seekSegmentTime';


    return html.replace(new RegExp('<(.*?)\\s+[^<]*(' + events + ')(\\s+||)(=|:)[^<]*>','gim'), '<$1>');
  },
  getUrlParams: function() {
    var hideDropdown = function(){
      if (typeof self.urlParams.dropdown != 'undefined' && self.urlParams.dropdown == 'hidden') {
        $('langDd').hide();
      }
    };

    var self = this;
    var pl     = /\+/g,  // Regex for replacing addition symbol with a space
      search = /([^&=]+)=?([^&]*)/g,
      decode = function (s) { return decodeURIComponent(s.replace(pl, ' ')); },
      query  = window.location.search.substring(1),
      match = search.exec(query);
    while (match) {
        self.urlParams[decode(match[1])] = decode(match[2]);
        match = search.exec(query);
    }
    hideDropdown();
  }
};
