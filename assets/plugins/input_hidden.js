/**
 * Modifies the provided hidden input so value changes to trigger events.
 *
 * After this method is called, any changes to the 'value' property of the
 * specified input will trigger a 'change' event, just like would happen
 * if the input was a text field.
 *
 * As explained in the following SO post, hidden inputs don't normally
 * trigger on-change events because the 'blur' event is responsible for
 * triggering a change event, and hidden inputs aren't focusable by virtue
 * of being hidden elements:
 * https://stackoverflow.com/a/17695525/4342230
 *
 * @param {HTMLInputElement} inputElement
 *   The DOM element for the hidden input element.
 */
function setupHiddenInputChangeListener(inputElement) {
  const propertyName = 'value';

  const {get: originalGetter, set: originalSetter} =
    findPropertyDescriptor(inputElement, propertyName);

  // We wrap this in a function factory to bind the getter and setter values
  // so later callbacks refer to the correct object, in case we use this
  // method on more than one hidden input element.
  const newPropertyDescriptor = ((_originalGetter, _originalSetter) => {
    return {
      set: function(value) {
        const currentValue = originalGetter.call(inputElement);

        // Delegate the call to the original property setter
        _originalSetter.call(inputElement, value);

        // Only fire change if the value actually changed.
        if (currentValue !== value) {
          inputElement.dispatchEvent(new Event('change'));
        }
      },

      get: function() {
        // Delegate the call to the original property getter
        return _originalGetter.call(inputElement);
      }
    }
  })(originalGetter, originalSetter);

  Object.defineProperty(inputElement, propertyName, newPropertyDescriptor);
};

/**
 * Search the inheritance tree of an object for a property descriptor.
 *
 * The property descriptor defined nearest in the inheritance hierarchy to
 * the class of the given object is returned first.
 *
 * Credit for this approach:
 * https://stackoverflow.com/a/38802602/4342230
 *
 * @param {Object} object
 * @param {String} propertyName
 *   The name of the property for which a descriptor is desired.
 *
 * @returns {PropertyDescriptor, null}
 */
function findPropertyDescriptor(object, propertyName) {
  if (object === null) {
    return null;
  }

  if (object.hasOwnProperty(propertyName)) {
    return Object.getOwnPropertyDescriptor(object, propertyName);
  }
  else {
    const parentClass = Object.getPrototypeOf(object);

    return findPropertyDescriptor(parentClass, propertyName);
  }
}