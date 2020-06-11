//
//  LogOutModel.swift
//  Streamly
//
//  Created by Rohit Singh on 6/11/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct LogoutResponseData : Codable {

    let response_code : Int?
    let success : Bool?
    let message : String?

    enum CodingKeys: String, CodingKey {

        case response_code = "response_code"
        case success = "success"
        case message = "message"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        response_code = try values.decodeIfPresent(Int.self, forKey: .response_code)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        message = try values.decodeIfPresent(String.self, forKey: .message)

    }
}
