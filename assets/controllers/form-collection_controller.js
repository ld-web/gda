import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        "collectionContainer",
        "rule",
    ];

    static values = {
        index: Number,
        prototype: String,
    }

    addCollectionElement(event)
    {
        const item = document.createElement('div');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);

        this.collectionContainerTarget.appendChild(item.firstElementChild);

        this.indexValue++;
    }

    removeCollectionElement(event)
    {
        const target = event.target;

        const ruleContainer = target.closest('[data-form-collection-target="rule"]');
        if (ruleContainer) {
            ruleContainer.remove();
        } else {
            console.error("removeCollectionElement", "cannot find parent rule container");
        }
    }

    ruleTargetConnected(element) {
        const removeButton = document.createElement('button');
        removeButton.classList.add('btn', 'btn-danger');
        removeButton.innerHTML = 'X';
        removeButton.dataset.action = "click->form-collection#removeCollectionElement";

        const container = document.createElement('div');
        container.classList.add('col-auto');
        container.appendChild(removeButton);

        element.appendChild(container);
    }
}
