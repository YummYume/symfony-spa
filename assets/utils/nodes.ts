/**
 * Finds all submit buttons related to a form element.
 *
 * @param {HTMLFormElement} formElement
 * @return {*}  {HTMLButtonElement[]}
 */
export const findSubmitButtonsForForm = (formElement: HTMLFormElement): HTMLButtonElement[] => {
  const formId = formElement.id;
  const submitChildButtons = formElement.querySelectorAll('button[type="submit"]:not([form])');
  const submitReferenceButtons = document.querySelectorAll(`button[type="submit"][form="${formId}"]`);

  return [...submitChildButtons, ...submitReferenceButtons] as HTMLButtonElement[];
};
