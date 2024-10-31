mutation appSubscriptionCreate(
    $name: String!,
    $returnUrl: URL!,
    $trialDays: Int,
    $test: Boolean,
    $lineItems: [AppSubscriptionLineItemInput!]!
) {
    appSubscriptionCreate(
        name: $name,
        returnUrl: $returnUrl,
        trialDays: $trialDays,
        test: $test,
        lineItems: $lineItems
    ) {
        appSubscription {
            id
        }
        confirmationUrl
        userErrors {
            field
            message
        }
    }
}
