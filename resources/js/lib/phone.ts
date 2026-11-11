export function formatBrazilianPhone(value: string): string {
    const digits = value.replace(/\D/g, '').slice(0, 11);

    if (digits.length <= 2) {
        return digits ? `(${digits}` : '';
    }

    const areaCode = digits.slice(0, 2);
    const subscriber = digits.slice(2);
    const prefixLength = digits.length > 10 ? 5 : 4;
    const formattedSubscriber = subscriber.length > prefixLength
        ? `${subscriber.slice(0, prefixLength)}-${subscriber.slice(prefixLength)}`
        : subscriber;

    return `(${areaCode}) ${formattedSubscriber}`;
}
